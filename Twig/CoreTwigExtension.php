<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 14/07/17
 * Time: 21:54
 */

namespace Umbrella\CoreBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigTest;
use Umbrella\CoreBundle\Utils\StringUtils;

/**
 * Class CoreTwigExtension
 */
class CoreTwigExtension extends AbstractExtension
{


    /**
     * @return array|\Twig_Filter[]
     */
    public function getFilters()
    {
        return array(
            new TwigFilter('to_css', array($this, 'toCss'), array('is_safe' => array('html'))),
            new TwigFilter('to_human_size', array($this, 'toHumanSize'), array('is_safe' => array('html'))),
        );
    }

    public function getTests()
    {
        return [
            new TwigTest('instanceof', array($this, 'isInstanceof'))
        ];
    }

    /**
     * @param array $rules
     * @return string
     */
    public function toCss(array $rules)
    {
        $css = '';
        foreach ($rules as $key => $value) {
            $css .= "$key:$value;";
        }
        return $css;
    }

    /**
     * @param $bytes
     * @return string
     */
    public function toHumanSize($bytes)
    {
        return StringUtils::to_human_size($bytes);
    }

    /**
     * @param $var
     * @param $instance
     * @return bool
     */
    public function isInstanceOf($var, $instance)
    {
        return $var instanceof $instance;
    }
}

