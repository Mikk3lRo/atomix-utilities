<?php
namespace Mikk3lRo\atomix\utilities;

use Mikk3lRo\atomix\system\DirConf;
use Mikk3lRo\atomix\io\LogTrait;
use Exception;

class Resources {
    static function fetchAndCacheResource($url, $name = null, $force_fresh = false) {
        $resourcesDir = DirConf::get('resources');

        if ($name === null) {
            $name = basename($url);
        }
        $local_file = $resourcesDir . '/downloads/' . $name;
        if ($force_fresh && is_file($local_file)) {
            unlink($local_file);
        }
        if (!is_file($local_file) || filesize($local_file) < 1024) {
            $cmd = "wget -O " . escapeshellarg($local_file) . "  --no-check-certificate --content-disposition " . escapeshellarg($url);
            passthru($cmd);
            clearstatcache();
            if (!is_file($local_file) || filesize($local_file) < 1024) {
                throw new Exception('Failed to download "' . $name . '" !?');
            }
        }
        return $local_file;
    }
}