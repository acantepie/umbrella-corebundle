<?php

namespace Umbrella\CoreBundle\Component\Menu\Model;

use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * Class BreadcrumbItem
 */
class BreadcrumbItem
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $icon;

    /**
     * @var string
     */
    public $label;

    /**
     * @var string|null
     */
    public $translationDomain;

    /**
     * @var string
     */
    public $route;

    /**
     * @var array
     */
    public $routeParams = [];

    /**
     * @param array $options
     *
     * @return BreadcrumbItem
     */
    public static function create(array $options = [])
    {
        $bi = new BreadcrumbItem();
        $bi->icon = ArrayUtils::get($options, 'icon');
        $bi->label = ArrayUtils::get($options, 'label');
        $bi->translationDomain = ArrayUtils::get($options, 'translation_domain', 'messages');
        $bi->route = ArrayUtils::get($options, 'route');
        $bi->routeParams = ArrayUtils::get($options, 'route_params', []);

        return $bi;
    }

    /**
     * @param MenuItem $item
     *
     * @return BreadcrumbItem
     */
    public static function createFromMenuItem(MenuItem $item)
    {
        $bi = new BreadcrumbItem();
        $bi->icon = $item->icon;
        $bi->label = $item->label;
        $bi->translationDomain = $item->translationDomain;
        $bi->route = $item->route;
        $bi->routeParams = $item->routeParams;

        return $bi;
    }
}
