<?php declare(strict_types = 1);

namespace Mikk3lRo\atomix\utilities;

class Formatters
{
    /**
     * Returns bytes formatted as KiB, MiB etc. rounded to reasonable precisions
     *
     * @param integer|float $uglybytes Number of bytes as integer or float.
     *
     * @return string
     */
    public static function niceBytes($uglybytes)
    {
        if ($uglybytes < 512) {
            return round($uglybytes) . 'B';
        } else if ($uglybytes < 1024 * 10) {
            return round($uglybytes / (1024), 1) . 'KiB';
        } else if ($uglybytes < 1024 * 512) {
            return round($uglybytes / (1024)) . 'KiB';
        } else if ($uglybytes < 1024 * 1024 * 10) {
            return round($uglybytes / (1024 * 1024), 1) . 'MiB';
        } else if ($uglybytes < 1024 * 1024 * 512) {
            return round($uglybytes / (1024 * 1024)) . 'MiB';
        } else if ($uglybytes < 1024 * 1024 * 1024 * 10) {
            return round($uglybytes / (1024 * 1024 * 1024), 1) . 'GiB';
        } else if ($uglybytes < 1024 * 1024 * 1024 * 512) {
            return round($uglybytes / (1024 * 1024 * 1024)) . 'GiB';
        } else if ($uglybytes < 1024 * 1024 * 1024 * 1024 * 10) {
            return round($uglybytes / (1024 * 1024 * 1024 * 1024), 1) . 'TiB';
        } else if ($uglybytes < 1024 * 1024 * 1024 * 1024 * 512) {
            return round($uglybytes / (1024 * 1024 * 1024 * 1024)) . 'TiB';
        } else if ($uglybytes < 1024 * 1024 * 1024 * 1024 * 1024 * 10) {
            return round($uglybytes / (1024 * 1024 * 1024 * 1024 * 1024), 1) . 'PiB';
        } else if ($uglybytes < 1024 * 1024 * 1024 * 1024 * 1024 * 512) {
            return round($uglybytes / (1024 * 1024 * 1024 * 1024 * 1024)) . 'PiB';
        } else if ($uglybytes < 1024 * 1024 * 1024 * 1024 * 1024 * 1024 * 10) {
            return round($uglybytes / (1024 * 1024 * 1024 * 1024 * 1024 * 1024), 1) . 'EiB';
        }
        return round($uglybytes / (1024 * 1024 * 1024 * 1024 * 1024 * 1024)) . 'EiB';
    }
}
