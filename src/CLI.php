<?php declare(strict_types = 1);

namespace Mikk3lRo\atomix\utilities;

class CLI
{
    /**
     * Gets a command that can be run to start the script that is currently
     * running. Useful fx. for automatically restarting daemons.
     *
     * This is not guaranteed to be the same command that was called, but it
     * *should* result in the same script being executed using the same
     * environment.
     *
     * @param array|null $args An array of arguments to the script - defaults to the same as the current.
     *
     * @global array $argv Uses the global arguments.
     *
     * @return string The command
     */
    public static function getCalledCommand(?array $args = null) : string
    {
        $cmd = self::getPhpCommand(self::getCalledFile());
        if ($args === null) {
            global $argv;
            $args = array_slice($argv, 1);
        }
        foreach ($args as $arg) {
            $cmd .= ' ' . escapeshellarg($arg);
        }
        return $cmd;
    }


    /**
     * Gets a command that runs a php script under the same php version and with
     * the same php.ini as the current script.
     *
     * @param string     $script The php script filename.
     * @param array|null $args   An array of arguments to the script - defaults to none.
     *
     * @global array $argv Uses the global arguments.
     *
     * @return string The command
     */
    public static function getPhpCommand(string $script, ?array $args = null) : string
    {
        $inipath = php_ini_loaded_file();

        $cmd = array();
        $cmd[] = escapeshellcmd(PHP_BINARY);
        if ($inipath) {
            $cmd[] = '--php-ini ' . escapeshellarg($inipath);
        }
        $cmd[] = '-f ' . escapeshellarg($script);
        if (is_array($args)) {
            foreach ($args as $arg) {
                $cmd[] = escapeshellarg($arg);
            }
        }
        return implode(' ', $cmd);
    }


    /**
     * Gets the topmost php-file, ie. the script that was called.
     *
     * @return string The absolute path to the script that was called.
     */
    public static function getCalledFile() : string
    {
        $options = defined("DEBUG_BACKTRACE_IGNORE_ARGS") ? DEBUG_BACKTRACE_IGNORE_ARGS : false;
        $backtrace = debug_backtrace($options);
        $topFrame = array_pop($backtrace);
        return isset($topFrame['file']) ? $topFrame['file'] : false;
    }
}
