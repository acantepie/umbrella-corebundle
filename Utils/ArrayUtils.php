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
     * Return true if all element of array $search are in array $all
     *
     * @param  array $a
     * @param  array $b
     * @return bool
     */
    public static function contains_all(array $search, array $all)
    {
        return count(array_intersect($search, $all)) === count($search);
    }

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
        $result = [];
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
    public static function array_map_recursive($f, array $xs)
    {
        $out = [];
        foreach ($xs as $k => $x) {
            $out[$k] = (is_array($x)) ? self::array_map_recursive($f, $x) : $f($x);
        }
        return $out;
    }

    /**
     * Convert
     * [
     *  a => [
     *      b => 1,
     *      c => 2
     *  ]
     * ]
     * to
     * [
     *  a.b => 1,
     *  a.c => 2
     * ]
     *
     * @param  array  $nested
     * @param  string $baseNs
     * @param  array  $stopRules
     * @return array
     */
    public static function remap_nested_array(array $nested, $baseNs = '', $stopRules = [])
    {
        $r = [];
        self::map_recursive_nested_array($nested, $baseNs, $stopRules, $r);
        return $r;
    }

    /**
     * @param $a
     * @param string $currentNs
     * @param array  $stopRules
     * @param array  $result
     */
    private static function map_recursive_nested_array($a, $currentNs = '', array $stopRules = [], array &$result)
    {
        if (is_array($a) && !in_array($currentNs, $stopRules)) {
            foreach ($a as $key => $value) {
                $ns = empty($currentNs) ? $key : sprintf('%s.%s', $currentNs, $key);
                self::map_recursive_nested_array($value, $ns, $stopRules, $result);
            }
        } else {
            $result[$currentNs] = $a;
        }
    }
}
