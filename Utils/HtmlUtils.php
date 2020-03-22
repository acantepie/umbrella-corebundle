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

    /**
     * @param $iconKey
     * @param $class
     * @return string
     */
    public static function render_icon($iconKey, $class = "")
    {
        if (preg_match('/fa-/', $iconKey)) {
            $iconKey = preg_replace('/fa\ |fa$/', '', $iconKey);
            return sprintf('<i class="fa %s %s"></i>', $iconKey, $class);
        }

        // material icon
        return sprintf('<i class="material-icons %s">%s</i>', $class, $iconKey);
    }
}