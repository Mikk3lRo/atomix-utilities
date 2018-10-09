<?php
namespace Mikk3lRo\atomix\utilities;

use Exception;

class Random
{
    /**
     * Generate a random string using all english upper- and lowercase letters and numbers.
     *
     * @param integer $length The desired length.
     *
     * @return string
     */
    public static function token(int $length = 32) : string
    {
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        return self::getString($length, $codeAlphabet);
    }


    /**
     * Generate a random string while:
     * - avoiding easily mistaken letters and numbers (capital I and O, lowercase l and o plus the numbers 0 and 1).
     * - making sure it contains at least one capital letter, one lowercase letter and one number.
     * - optionally including at least one "special" character.
     *
     * @param integer $length         The desired length.
     * @param boolean $includeSpecial True to include (and ensure at least one) special character.
     *
     * @return string
     */
    public static function password(int $length = 8, bool $includeSpecial = false) : string
    {
        $codeAlphabet = array(
            "ABCDEFGHJKLMNPQRSTUVWXYZ",
            "abcdefghijkmnpqrstuvwxyz",
            "23456789"
        );
        if ($includeSpecial) {
            $codeAlphabet[] = '!#%&/()=?+-€$@£';
        }

        if ($length > count($codeAlphabet)) {
            //Get a base that is 3-4 characters shorter than desired
            $baseString = self::getString($length - count($codeAlphabet), implode('', $codeAlphabet));
        } else {
            $baseString = '';
        }

        //Then add one of each type
        foreach ($codeAlphabet as $required) {
            $baseString .= self::getString(1, $required);
        }

        //And shuffle the string to avoid a predictable pattern
        return str_shuffle($baseString);
    }


    /**
     * Generalized function to generate a random string.
     *
     * @param integer $length   Desired length.
     * @param string  $alphabet Possible characters.
     *
     * @return string
     */
    private static function getString(int $length, string $alphabet) : string
    {
        $max = strlen($alphabet);

        $token = '';
        for ($i = 0; $i < $length; $i++) {
            $token .= $alphabet[self::integer(0, $max - 1)];
        }

        return $token;
    }


    /**
     * Get a random integer - tries to return a cryptographically safe random integer, but falls back to the standard mt_rand() function.
     *
     * @param integer $min Minimum value.
     * @param integer $max Maximum value.
     *
     * @return integer
     */
    public static function integer(int $min, int $max) : int
    {
        try {
            return random_int($min, $max);
        } catch (Exception $e) { // @codeCoverageIgnoreStart
            return mt_rand($min, $max);
        }                        // @codeCoverageIgnoreEnd
    }
}
