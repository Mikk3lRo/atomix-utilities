<?php declare(strict_types = 1);

namespace Mikk3lRo\atomix\utilities;

use Exception;

class Resources
{
    /**
     * Fetches a remote file if it does not already exist in the expected local path.
     *
     * @param string  $url       The URL of the remote file.
     * @param string  $localFile The local path to the file.
     * @param integer $timeout   Maximum time to wait for the download.
     *
     * @return string Returns either 'hit' or 'miss' depending if the file was already cached.
     *
     * @throws Exception Throws an exception if the download fails or the file is empty.
     */
    public static function fetchRemoteResourceOrCache(string $url, string $localFile, int $timeout = 300) : string
    {
        if (is_file($localFile) && filesize($localFile) > 0) {
            return 'hit';
        }
        self::fetchRemoteResource($url, $localFile, $timeout);
        clearstatcache();
        if (!is_file($localFile) || filesize($localFile) == 0) {
            throw new Exception(sprintf('Failed to download "%s" to "%s" !?', $url, $localFile)); //@codeCoverageIgnore
        }
        return 'miss';
    }


    /**
     * Fetches a remote file to a local path, overwriting it if it already exists.
     *
     * @param string  $url       The URL of the remote file.
     * @param string  $localFile The local path to the file.
     * @param integer $timeout   Maximum time to wait for the download.
     *
     * @return void
     *
     * @throws Exception Throws an exception if the download fails or the file is empty.
     */
    public static function fetchRemoteResource(string $url, string $localFile, int $timeout = 300) : void
    {
        $tempfile = tempnam(dirname($localFile), '.tmp');

        $fp = fopen($tempfile, 'w+b');
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_exec($ch);
        fclose($fp);
        $curlErrNo = curl_errno($ch);
        $curlError = curl_error($ch);
        curl_close($ch);
        if ($curlErrNo) {
            unlink($tempfile);
            throw new Exception(sprintf('Failed to download "%s" to "%s" !?' . "\n" . '%s', $url, $tempfile, $curlError));
        }
        if (file_exists($localFile)) {
            unlink($localFile);
        }
        rename($tempfile, $localFile);
    }
}
