<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 16:51.
 */

namespace Umbrella\CoreBundle\Component\Menu;

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
            $this->menus[$name] = $factory->$method(new MenuBuilder());
        }

        return $this->menus[$name];
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
