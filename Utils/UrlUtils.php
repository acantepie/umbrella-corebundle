<?php


namespace Umbrella\CoreBundle\Utils;


class UrlUtils
{
    /**
     * http_build_query build invalid query
     * foo[0]=1&foo[1]=2 => foo[]=1&foo[]=2
     *
     * @param $encodeduri
     * @return string|null
     */
    public static function fix_invalid_basket($encodeduri)
    {
        return preg_replace('/%5B[0-9]+%5D/simU', '%5B%5D', $encodeduri);
    }

}