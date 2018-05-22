<?php
namespace Mikk3lRo\atomix\utilities;

class Reflections
{
    /**
     * Checks that all arguments for a callable function - including optional
     * arguments - exist in an array, and that there are no extra arguments.
     *
     * The type is NOT checked.
     *
     * Useful fx. when we require an array with a description of each argument.
     *
     * @param callable $callable  The function.
     * @param array    $arguments The arguments.
     *
     * @return void
     */
    public static function checkArgumentsExistExact(callable $callable, array $arguments) : void
    {
        self::checkArguments($callable, $arguments, false, false);
    }


    /**
     * Returns a "flat" array for use with call_user_func.
     *
     * In the process non-optional arguments are checked for existence.
     *
     * @param callable $callable  The function.
     * @param array    $arguments The arguments.
     *
     * @return array Returns a "flat" array for use with call_user_func.
     */
    public static function getArgumentArrayForCallUserFunc(callable $callable, array $arguments) : array
    {
        return self::checkArguments($callable, $arguments, true, true);
    }


    /**
     * Internal function to match arguments to a function.
     *
     * @param callable $callable               The function.
     * @param array    $arguments              The arguments.
     * @param boolean  $allowMissingIfOptional Whether to check that arguments that are optional in the function declaration exist in the array.
     * @param boolean  $allowUnused            Whether to check for extra elements in the array.
     *
     * @return array Returns a "flat" array for use with call_user_func.
     *
     * @throws \Exception When one of the checks fail.
     */
    private static function checkArguments(callable $callable, array $arguments, bool $allowMissingIfOptional = false, bool $allowUnused = false) : array
    {
        if (is_array($callable)) {
            $refFunc = new \ReflectionMethod($callable[0], $callable[1]);
        } else {
            $refFunc = new \ReflectionFunction($callable);
        }
        $actualArgs = array();
        $arrayForCallUserFunc = array();
        foreach ($refFunc->getParameters() as $refParm) {
            $actualArgs[] = $refParm->name;

            $isAllowedToNotExist = $allowMissingIfOptional && $refParm->isOptional();
            if (!isset($arguments[$refParm->name]) && !$isAllowedToNotExist) {
                throw new \Exception('Argument "' . $refParm->name . '" is missing.');
            } else if (!isset($arguments[$refParm->name])) {
                $arrayForCallUserFunc[] = $refParm->getDefaultValue();
            } else {
                $arrayForCallUserFunc[] = $arguments[$refParm->name];
            }
        }

        if (!$allowUnused) {
            foreach ($arguments as $expectedArgument => $description) {
                if (!in_array($expectedArgument, $actualArgs)) {
                    throw new \Exception('Argument "' . $expectedArgument . '" is unused.');
                }
            }
        }
        return $arrayForCallUserFunc;
    }
}
