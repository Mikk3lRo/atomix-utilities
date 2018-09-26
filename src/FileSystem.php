<?php
namespace Mikk3lRo\atomix\utilities;

class FileSystem
{
    /**
     * Recursive glob using the double-asterix.
     *
     * @param string  $pattern The glob-pattern.
     * @param integer $flags   Glob flags (passed to "normal glob").
     *
     * @return array Returns an array of matching files.
     */
    public static function glob(string $pattern, int $flags = 0) : array
    {
        if (strpos($pattern, '/**/') === false) {
            return glob($pattern, $flags);
        }

        $patternParts = explode('/**/', $pattern);

        // Get sub dirs
        $dirs = glob(array_shift($patternParts) . '/*', GLOB_ONLYDIR | GLOB_NOSORT);

        // Get files for current dir
        $files = glob($pattern, $flags);

        foreach ($dirs as $dir) {
            $subDirContent = self::glob($dir . '/**/' . implode('/**/', $patternParts), $flags);
            $files = array_merge($files, $subDirContent);
        }

        return $files;
    }
}
