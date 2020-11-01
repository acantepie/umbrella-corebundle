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
     *
     * @return string
     */
    public static function array_to_html_attribute(array $attr)
    {
        $html = '';
        foreach ($attr as $key => $value) {
            $html .= $key . '="' . self::encode_html_attr($value) . '" ';
        }

        return $html;
    }

    /**
     * @param $value
     *
     * @return string
     */
    public static function encode_html_attr($value)
    {
        if (is_array($value)) {
            $value = json_encode($value);

            if (false === $value) {
                throw new \JsonException('Enable to encode json_data');
            }
        }

        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
    }

    /**
     * @param $class
     *
     * @return string
     */
    public static function render_icon($class)
    {
        return empty($class) ? '' : sprintf('<i class="%s"></i>', $class);
    }
}
