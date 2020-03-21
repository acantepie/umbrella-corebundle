<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 16:51.
 */

namespace Umbrella\CoreBundle\Component\Menu;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Umbrella\AdminBundle\Menu\SidebarMenu;
use Umbrella\CoreBundle\Component\Menu\Model\Menu;

/**
 * Class MenuProvider.
 */
class MenuProvider
{
    /**
     * @var array
     */
    private $menus = array();

    /**
     * @var array
     */
    private $cachedMenus = array();

    /**
     * @var MenuBuilder
     */
    private $builder;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * MenuProvider constructor.
     *
     * @param ContainerInterface $container
     * @param MenuBuilder        $builder
     */
    public function __construct(ContainerInterface $container, MenuBuilder $builder)
    {
        $this->container = $container;
        $this->builder = $builder;
    }

    /**
     * @param $alias
     * @param $id
     * @param $method
     */
    public function register($alias, $id, $method)
    {
        // alias already registered
        if ($id == SidebarMenu::class && array_key_exists($alias, $this->menus)) {
            return;
        }

        $this->menus[$alias] = [$id, $method];
    }

    /**
     * @param $name
     *
     * @return Menu
     */
    public function get($name)
    {
        if (!isset($this->menus[$name])) {
            throw new \InvalidArgumentException(sprintf('The menu "%s" is not defined.', $name));
        }

        if (!is_array($this->menus[$name]) || 2 !== count($this->menus[$name])) {
            throw new \InvalidArgumentException(sprintf('The service definition for menu "%s" is invalid. It should be an array (serviceId, method)', $name));
        }

        if (!array_key_exists($name, $this->cachedMenus)) {
            list($id, $method) = $this->menus[$name];
            $this->cachedMenus[$name] = $this->container->get($id)->$method($this->builder);
        }

        return $this->cachedMenus[$name];
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function has($name)
    {
        return isset($this->menus[$name]);
    }
}
