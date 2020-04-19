<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 09/02/18
 * Time: 20:07
 */

namespace Umbrella\CoreBundle\Component\DataTable\RowAction;

/**
 * Class RowAction
 */
class RowAction
{
    const TARGET_SELF = '_self';
    const TARGET_BLANK = '_blank';

    /**
     * @var string
     */
    private $icon;

    /**
     * @var string
     */
    private $class = 'action-icon';

    /**
     * @var string
     */
    private $route;

    /**
     * @var array
     */
    private $routeParams = array();

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $confirm;

    /**
     * @var boolean
     */
    private $xhr;

    /**
     * @var string
     */
    private $target;

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     * @return RowAction
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $class
     * @return RowAction
     */
    public function setClass($class)
    {
        $this->class = $class;
        return $this;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param $route
     * @param array $routeParams
     * @return $this
     */
    public function setRoute($route, array $routeParams = array())
    {
        $this->route = $route;
        $this->routeParams = $routeParams;
        return $this;
    }

    /**
     * @return array
     */
    public function getRouteParams()
    {
        return $this->routeParams;
    }

    /**
     * @param array $routeParams
     * @return RowAction
     */
    public function setRouteParams(array $routeParams)
    {
        $this->routeParams = $routeParams;
        return $this;
    }

    public function hasRouteParam($key)
    {
        return array_key_exists($key, $this->routeParams);
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function addRouteParam($key, $value)
    {
        $this->routeParams[$key] = $value;
        return $this;
    }

    /**
     * @param $key
     * @return $this
     */
    public function removeRouteParam($key)
    {
        if ($this->hasRouteParam($key)) {
            unset($this->routeParams[$key]);
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return RowAction
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return RowAction
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getConfirm()
    {
        return $this->confirm;
    }

    /**
     * @param string $confirm
     * @return RowAction
     */
    public function setConfirm($confirm)
    {
        $this->confirm = $confirm;
        return $this;
    }

    /**
     * @return bool
     */
    public function isXhr()
    {
        return $this->xhr;
    }

    /**
     * @param bool $xhr
     * @return RowAction
     */
    public function setXhr($xhr)
    {
        $this->xhr = $xhr;
        return $this;
    }

    /**
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param string $target
     * @return RowAction
     */
    public function setTarget($target)
    {
        $this->target = $target;
        return $this;
    }






}