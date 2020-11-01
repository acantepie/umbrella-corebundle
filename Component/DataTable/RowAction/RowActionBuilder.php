<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 09/02/18
 * Time: 21:15
 */

namespace Umbrella\CoreBundle\Component\DataTable\RowAction;

/**
 * FIXME : Use ListActionBuilder instead of row Action ?
 *
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
     * @param array $routeParams
     *
     * @return RowAction
     */
    public function createXhrEdit($route, array $routeParams = [])
    {
        return $this->createEdit($route, $routeParams)
            ->setXhr(true);
    }

    /**
     * @param $route
     * @param $routeParams
     *
     * @return RowAction
     */
    public function createEdit($route, array $routeParams = [])
    {
        return $this->create()
            ->setRoute($route, $routeParams)
            ->setIcon('mdi mdi-pencil')
            ->setTitle('action.edit');
    }

    /**
     * @param $route
     * @param $routeParams
     *
     * @return RowAction
     */
    public function createXhrDelete($route, array $routeParams = [])
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
     *
     * @return RowAction
     */
    public function createXhrShow($route, array $routeParams = [])
    {
        return $this->createShow($route, $routeParams)
            ->setXhr(true);
    }

    /**
     * @param $route
     * @param $routeParams
     *
     * @return RowAction
     */
    public function createShow($route, array $routeParams = [])
    {
        return $this->create()
            ->setRoute($route, $routeParams)
            ->setIcon('mdi mdi-eye')
            ->setTitle('action.show');
    }

    /**
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }
}
