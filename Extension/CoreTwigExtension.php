<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 14/07/17
 * Time: 21:54
 */

namespace Umbrella\CoreBundle\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigTest;
use Umbrella\CoreBundle\Component\UmbrellaFile\UmbrellaFileHelper;
use Umbrella\CoreBundle\Entity\UmbrellaFile;
use Umbrella\CoreBundle\Utils\HtmlUtils;
use Umbrella\CoreBundle\Utils\StringUtils;

/**
 * Class CoreTwigExtension
 */
class CoreTwigExtension extends AbstractExtension
{

    /** @var UmbrellaFileHelper */
    protected $fileHelper;

    /**
     * CoreTwigExtension constructor.
     * @param UmbrellaFileHelper $fileHelper
     */
    public function __construct(UmbrellaFileHelper $fileHelper)
    {
        $this->fileHelper = $fileHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new TwigFilter('to_css', [$this, 'toCss'], ['is_safe' => ['html']]),
            new TwigFilter('to_human_size', [$this, 'toHumanSize'], ['is_safe' => ['html']]),
            new TwigFilter('icon', [$this, 'renderIcon'], ['is_safe' => ['html']]),
            new TwigFilter('html_attributes', [$this, 'toHtmlAttribute'], ['is_safe' => ['html']]),
            new TwigFilter('get_thumb', [$this, 'getThumb'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getTests()
    {
        return [
            new TwigTest('instanceof', [$this, 'isInstanceof']),
        ];
    }

    /**
     * @param array $rules
     *
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
     *
     * @return string
     */
    public function toHumanSize($bytes)
    {
        return StringUtils::to_human_size($bytes);
    }

    /**
     * @param $var
     * @param $instance
     *
     * @return bool
     */
    public function isInstanceOf($var, $instance)
    {
        return $var instanceof $instance;
    }

    /**
     * @param string $class
     *
     * @return string
     */
    public function renderIcon($class)
    {
        return HtmlUtils::render_icon($class);
    }

    /**
     * @param $attributes
     *
     * @return string
     */
    public function toHtmlAttribute($attributes)
    {
        return HtmlUtils::array_to_html_attribute($attributes);
    }

    public function getThumb(UmbrellaFile $file, $filter = null) {
        if (null === $file) {
            return "";
        }
        return $this->fileHelper->getImageUrl($file, $filter);
    }
}
