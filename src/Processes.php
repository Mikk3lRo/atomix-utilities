<?php
namespace Mikk3lRo\atomix\utilities;

class Processes
{
    /**
     * Execute a command without blocking and return the PID
     *
     * @param string $cmd      The command to execute.
     * @param string $stdOut   A filename to redirect stdout to.
     * @param string $errorOut A filename to redirect stderr to.
     *
     * @return integer Returns the PID of the process.
     */
    public static function executeNonBlocking(string $cmd, string $stdOut = '/dev/null', string $errorOut = '/dev/null') : int
    {
        $cmd = trim($cmd, ';');
        $runCmd = 'nohup';
        //If we have more than one command wrap them in a bash process
        //- otherwise only the last will run in the background.
        if (strpos($cmd, ';') !== false) {
            $runCmd .= ' bash -c ' . escapeshellarg($cmd);
        } else {
            $runCmd .= ' ' . $cmd;
        }
        $runCmd .= ' > ' . escapeshellarg($stdOut);
        $runCmd .= ' 2> ' . escapeshellarg($errorOut);
        $runCmd .= ' & echo $!';

        return intval(`$runCmd`);
    }


    /**
     * Check if a process is running.
     *
     * @param integer $pid The PID of the process.
     *
     * @return boolean True if process is running.
     */
    public static function isRunning(int $pid) : bool
    {
        if (function_exists('posix_getpgid')) {
            //use posix if available
            return (posix_getpgid($pid) !== false);
        } else { // @codeCoverageIgnoreStart
            //ps will return an error if the pid does not exist.
            return `ps -p $pid > /dev/null 2>&1; echo $?` === '0';
        }
        // @codeCoverageIgnoreEnd
    }
}
