<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 08/05/17
 * Time: 16:58.
 */

namespace Umbrella\CoreBundle\Component\Menu\Extension;

use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use Umbrella\CoreBundle\Component\Menu\MenuHelper;
use Umbrella\CoreBundle\Component\Menu\Model\Menu;
use Umbrella\CoreBundle\Component\Menu\Model\MenuNode;

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
            new TwigFunction('menu_get', [$this, 'getMenu']),
            new TwigFunction('menu_render', [$this, 'render'], ['is_safe' => ['html']]),
            new TwigFunction('menu_is_granted_node', [$this, 'isGranted']),
            new TwigFunction('menu_is_current_node', [$this, 'isCurrent']),
            new TwigFunction('menu_get_current_node', [$this, 'getCurrentNode']),
            new TwigFunction('menu_get_breadcrumb', [$this, 'getBreadcrumb']),
        ];
    }

    /**
     * @param $name
     *
     * @return Menu
     */
    public function getMenu($name)
    {
        return $this->helper->getMenu($name);
    }

    /**
     * @param $name
     *
     * @return string
     */
    public function render($name)
    {
        $menu = $this->helper->getMenu($name);
        return $this->helper->getRenderer($name)->render($menu);
    }

    /**
     * @param MenuNode $node
     *
     * @return bool
     */
    public function isGranted(MenuNode $node)
    {
        return $this->helper->isGranted($node);
    }

    /**
     * @param  MenuNode $node
     * @return bool
     */
    public function isCurrent(MenuNode $node)
    {
        return $this->helper->isCurrent($node);
    }

    /**
     * @param $name
     * @return null|MenuNode
     */
    public function getCurrentNode($name)
    {
        $menu = $this->helper->getMenu($name);
        return $this->helper->getCurrentNode($menu);
    }

    /**
     * @param $name
     * @return array
     */
    public function getBreadcrumb($name)
    {
        $menu = $this->helper->getMenu($name);
        return $this->helper->buildBreadcrumb($menu);
    }
}
