<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 07/06/17
 * Time: 23:40
 */

namespace Umbrella\CoreBundle\Utils;

/**
 * Class MathUtils
 */
class MathUtils
{
    /**
     * @see Utils.js
     *
     * @param $bytes
     * @param int $precision
     * @return string
     */
    public static function bytes_to_size($bytes, $precision = 2) {
        if (!$bytes) {
            return 0;
        }

        $units = array('Bytes', 'KB', 'MB', 'GB', 'TB');
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        // Uncomment one of the following alternatives
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

}