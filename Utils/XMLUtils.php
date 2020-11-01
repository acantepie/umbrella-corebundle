<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 03/05/18
 * Time: 14:07
 */

namespace Umbrella\CoreBundle\Utils;

class XMLUtils
{
    /**
     * @see https://stackoverflow.com/questions/6167279/converting-a-simplexml-object-to-an-array?utm_medium=organic&utm_source=google_rich_qa&utm_campaign=google_rich_qa
     *
     * @param mixed $xmlObject
     */
    public static function xml2array($xmlObject)
    {
        return json_decode(json_encode((array) $xmlObject), true);
    }
}
