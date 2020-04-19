<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 09/02/18
 * Time: 21:15
 */

namespace Umbrella\CoreBundle\Component\Table\RowAction;

/**
 * Class RowActionBuilder
 */
class RowActionBuilder
{
    /**
     * @var array
     */
    private $actions = [];

    /**
     * @return RowAction
     */
    public function create()
    {
        $action = new RowAction();
        $this->actions[] = $action;
        return $action;
    }

    /**
     * @return $this
     */
    public function clear()
    {
        $this->actions = [];
        return $this;
    }

    /**
     * @param $route
     * @param $routeParams
     * @return RowAction
     */
    public function createEdit($route, array $routeParams = array())
    {
        return $this->create()
            ->setRoute($route, $routeParams)
            ->setIcon('mdi mdi-pencil')
            ->setTitle('action.edit')
            ->setXhr(true);
    }

    /**
     * @param $route
     * @param $routeParams
     * @return RowAction
     */
    public function createDelete($route, array $routeParams = array())
    {
        return $this->create()
            ->setRoute($route, $routeParams)
            ->setIcon('mdi mdi-delete')
            ->setTitle('action.delete')
            ->setConfirm('message.delete_confirm')
            ->setXhr(true);
    }

    /**
     * @param $route
     * @param $routeParams
     * @return RowAction
     */
    public function createShow($route, array $routeParams = array())
    {
        return $this->create()
            ->setRoute($route, $routeParams)
            ->setIcon('eye')
            ->setTitle('action.show')
            ->setXhr(true);
    }

    /**
     * @param $route
     * @param array $routeParams
     * @return RowAction
     */
    public function createAddChild($route, array $routeParams = array())
    {
        return $this->create()
            ->setRoute($route, $routeParams)
            ->setTitle('action.add_child')
            ->setIcon('add')
            ->setXhr(true);
    }

    /**
     * @array
     */
    public function getActions()
    {
        return $this->actions;
    }
}