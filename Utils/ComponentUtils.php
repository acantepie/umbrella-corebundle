<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 18/04/20
 * Time: 22:41
 */

namespace Umbrella\CoreBundle\Utils;


class ComponentUtils
{
    /**
     * @param $typeClass
     * @return mixed|string
     */
    public static function typeClassToId($typeClass)
    {
        $ns = preg_replace('/Type$/', '', $typeClass);
        $name = str_replace('\\', '_', $ns);
        return \function_exists('mb_strtolower') && preg_match('//u', $name) ? mb_strtolower($name, 'UTF-8') : strtolower($name);
    }

}