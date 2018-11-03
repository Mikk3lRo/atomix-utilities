<?php declare(strict_types = 1);

namespace Mikk3lRo\atomix\utilities;

use Exception;

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
     * @param array|callable $callable  The function.
     * @param array          $arguments The arguments.
     *
     * @return void
     */
    public static function checkArgumentsExistExact(/*no type hint to support protected and private*/ $callable, array $arguments) : void
    {
        self::checkArguments($callable, $arguments, false, false);
    }


    /**
     * Returns a "flat" array for use with call_user_func.
     *
     * In the process non-optional arguments are checked for existence.
     *
     * @param array|callable $callable  The function.
     * @param array          $arguments The arguments.
     *
     * @return array Returns a "flat" array for use with call_user_func.
     */
    public static function getArgumentArrayForCallUserFunc(/*no type hint to support protected and private*/ $callable, array $arguments) : array
    {
        return self::checkArguments($callable, $arguments, true, true);
    }


    /**
     * Ensure a function accepts at least the specified arguments.
     *
     * @param array|callable $callable           The function.
     * @param integer        $requiredArguments  The minimum (or exact if $allowExtraOptional=false) number of arguments the function must accept.
     * @param boolean        $allowExtraOptional Default true, which means the function may have additional parameters, as long as they are optional.
     *
     * @throws Exception If the function does not live up to the test.
     *
     * @return void
     */
    public static function requireFunctionToAcceptArgs(/*no type hint to support protected and private*/ $callable, int $requiredArguments, bool $allowExtraOptional = true) : void
    {
        if (is_array($callable)) {
            $refFunc = new \ReflectionMethod($callable[0], $callable[1]);
        } else {
            $refFunc = new \ReflectionFunction($callable);
        }

        $refParms = $refFunc->getParameters();

        if (count($refParms) > $requiredArguments) {
            if (!$allowExtraOptional) {
                throw new Exception('Function accepts too many parameters!');
            }
            for ($parmId = $requiredArguments; $parmId < count($refParms); $parmId++) {
                if (!$refParms[$parmId]->isOptional()) {
                    throw new Exception('Function requires too many parameters!');
                }
            }
        } else if (count($refParms) < $requiredArguments) {
            throw new Exception('Function accepts too few parameters!');
        }
    }


    /**
     * Internal function to match arguments to a function.
     *
     * @param array|callable $callable               The function.
     * @param array          $arguments              The arguments.
     * @param boolean        $allowMissingIfOptional Whether to check that arguments that are optional in the function declaration exist in the array.
     * @param boolean        $allowUnused            Whether to check for extra elements in the array.
     *
     * @return array Returns a "flat" array for use with call_user_func.
     *
     * @throws Exception When one of the checks fail.
     */
    private static function checkArguments(/*no type hint to support protected and private*/ $callable, array $arguments, bool $allowMissingIfOptional = false, bool $allowUnused = false) : array
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
                throw new Exception('Argument "' . $refParm->name . '" is missing.');
            } else if (!isset($arguments[$refParm->name])) {
                $arrayForCallUserFunc[] = $refParm->getDefaultValue();
            } else {
                $arrayForCallUserFunc[] = $arguments[$refParm->name];
            }
        }

        if (!$allowUnused) {
            foreach ($arguments as $expectedArgument => $description) {
                if (!in_array($expectedArgument, $actualArgs)) {
                    throw new Exception('Argument "' . $expectedArgument . '" is unused.');
                }
            }
        }
        return $arrayForCallUserFunc;
    }


    /**
     * Ensure a function declares the specified return type.
     *
     * @param array|callable $callable The function.
     * @param string         $type     The required return type.
     *
     * @return void
     *
     * @throws Exception If the function does not declare the correct return type.
     */
    public static function requireFunctionToHaveReturnType(/*no type hint to support protected and private*/ $callable, string $type) : void
    {
        if (is_array($callable)) {
            $refFunc = new \ReflectionMethod($callable[0], $callable[1]);
        } else {
            $refFunc = new \ReflectionFunction($callable);
        }

        if (!$refFunc->hasReturnType()) {
            throw new Exception('Function must declare return type!');
        } else if ($refFunc->getReturnType()->getName() !== $type) {
            throw new Exception(sprintf('Function return type must be %s!', $type));
        }
    }
}
