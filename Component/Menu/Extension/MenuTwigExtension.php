<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 08/05/17
 * Time: 16:58.
 */

namespace Umbrella\CoreBundle\Component\Menu\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Umbrella\CoreBundle\Component\Menu\MenuHelper;

/**
 * Class MenuTwigExtension.
 */
class MenuTwigExtension extends AbstractExtension
{
    /**
     * @var MenuHelper
     */
    private $helper;

    /**
     * MenuTwigExtension constructor.
     *
     * @param MenuHelper $helper
     */
    public function __construct(MenuHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('menu', [$this->helper, 'getMenu']),
            new TwigFunction('menu_render', [$this->helper, 'renderMenu'], ['is_safe' => ['html']]),
            new TwigFunction('menu_is_granted_item', [$this->helper, 'isGranted']),
            new TwigFunction('menu_is_current_item', [$this->helper, 'isCurrent']),
            new TwigFunction('menu_get_current_item', [$this->helper, 'getCurrentItem']),

            new TwigFunction('breadcrumb', [$this->helper, 'getBreadcrumb']),
            new TwigFunction('breadcrumb_render', [$this->helper, 'renderBreadcrumb'], ['is_safe' => ['html']]),
        ];
    }
}
