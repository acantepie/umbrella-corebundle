<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 28/06/17
 * Time: 19:06
 */

namespace Umbrella\CoreBundle\Component\Toolbar;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Umbrella\CoreBundle\Component\Toolbar\Model\Action;

/**
 * Class ActionsBuilder
 */
class ActionsBuilder
{

    /**
     * @var ActionFactory
     */
    private $factory;

    /**
     * @var array
     */
    private $actions = array();

    /**
     * ActionsBuilder constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->factory = $container->get(ActionFactory::class);
    }

    /**
     * @param $id
     * @param $actionClass
     * @param array $options
     *
     * @return $this
     */
    public function add($id, $actionClass, array $options = array())
    {
        $this->actions[$id] = array(
            'class' => $actionClass,
            'options' => $options,
        );

        return $this;
    }

    /**
     * @param $id
     * @return Action
     * @throws \Exception
     */
    public function get($id)
    {
        if (isset($this->actions[$id]['resolved'])) {
            return $this->actions[$id]['resolved'];
        }

        if (isset($this->actions[$id])) {
            $this->resolveAction($id);
            return $this->actions[$id]['resolved'];
        }

        throw new \Exception(sprintf('The action with id "%s" does not exist.', $id));

    }

    /**
     * @param $id
     * @return $this
     */
    public function remove($id)
    {
        unset($this->actions[$id]);
        return $this;
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function has($id)
    {
        return isset($this->actions[$id]);
    }

    /**
     *
     */
    public function getActions()
    {
        $this->resolveActions();
        $actionsResolved = array();
        foreach ($this->actions as $arg) {
            $actionsResolved[] = $arg['resolved'];
        }
        return $actionsResolved;
    }

    /**
     *
     */
    public function resolveActions()
    {
        foreach ($this->actions as $id => $action) {
            if (!isset($action['resolved'])) {
                $this->resolveAction($id);
            }
        }
    }

    /**
     * @param $id
     */
    protected function resolveAction($id)
    {
        $this->actions[$id]['options']['id'] = $id;
        $this->actions[$id]['resolved'] = $this->factory->create($this->actions[$id]['class'], $this->actions[$id]['options']);
    }

}