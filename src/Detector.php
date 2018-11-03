<?php declare(strict_types = 1);

namespace Mikk3lRo\atomix\utilities;

class Detector
{
    /**
     * Detects if we are running inside a docker container.
     *
     * @return boolean True if we are.
     */
    public static function isInsideDocker() : bool
    {
        return file_exists('/.dockerenv');
    }
}
