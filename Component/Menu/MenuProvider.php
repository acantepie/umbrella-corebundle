<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 16:51.
 */

namespace Umbrella\CoreBundle\Component\Menu;

use Symfony\Component\Routing\RouterInterface;
use Umbrella\CoreBundle\Component\Menu\Model\Menu;

/**
 * Class MenuProvider.
 */
class MenuProvider
{
    /**
     * @var array
     */
    private $menuFactories = array();

    /**
     * @var array
     */
    private $menus = array();

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * MenuProvider constructor.
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param $alias
     * @param $factory
     * @param $method
     */
    public function register($alias, $factory, $method)
    {
        $this->menuFactories[$alias] = [$factory, $method];
    }

    /**
     * @param $name
     *
     * @return Menu
     */
    public function get($name)
    {
        if (!isset($this->menuFactories[$name])) {
            throw new \InvalidArgumentException(sprintf('The menu "%s" is not defined.', $name));
        }

        if (!array_key_exists($name, $this->menus)) {
            list($factory, $method) = $this->menuFactories[$name];
            $this->menus[$name] = $factory->$method($this->createBuilder());
        }

        return $this->menus[$name];
    }

    /**
     * @return MenuBuilder
     */
    private function createBuilder()
    {
        return new MenuBuilder($this->router);
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
