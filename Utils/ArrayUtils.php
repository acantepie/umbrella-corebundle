<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 21:17.
 */

namespace Umbrella\CoreBundle\Utils;

/**
 * Class ArrayUtils.
 */
class ArrayUtils
{
    /**
     * @param array $array
     * @param $key
     * @param null $default
     *
     * @return mixed|null
     */
    public static function get(array $array, $key, $default = null)
    {
        return isset($array[$key]) ? $array[$key] : $default;
    }

    /**
     * @param array $array
     * @param $key
     * @param null $default
     *
     * @return mixed|null
     */
    public static function get_with_dot_keys(array $array, $key, $default = null)
    {
        $keys = explode('.', $key);

        $current = $array;
        foreach ($keys as $key) {
            if (is_array($current) && array_key_exists($key, $current)) {
                $current = $current[$key];
            } else {
                return $default;
            }
        }
        return $current;
    }

    /**
     * convert [0 => 'a', 1 => 'b', ...] to ['a'=>'a', 'b'=>'b', ...].
     *
     * @param array $array
     *
     * @return array
     */
    public static function values_as_keys(array  $array)
    {
        $result = array();
        foreach ($array as $value) {
            $result[$value] = $value;
        }

        return $result;
    }

    /**
     * @param $f
     * @param $xs
     * @return array
     */
    public static function array_map_recursive($f, array $xs) {
        $out = [];
        foreach ($xs as $k => $x) {
            $out[$k] = (is_array($x)) ? self::array_map_recursive($f, $x) : $f($x);
        }
        return $out;
    }
}
