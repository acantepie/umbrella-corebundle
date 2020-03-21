<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 08/06/17
 * Time: 21:53
 */

namespace Umbrella\CoreBundle\Utils;

/**
 * Class HtmlUtils
 */
class HtmlUtils
{
    /**
     * @param array $attr
     * @return string
     */
    public static function array_to_html_attribute(array $attr)
    {
        $html = '';
        foreach ($attr as $key => $value) {
            $html .= $key . '="' . htmlspecialchars($value) . '" ';
        }
        return $html;
    }
}