<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 26/03/20
 * Time: 22:19
 */

namespace Umbrella\CoreBundle\Component\Action;

/**
 * Class ActionListBuilder
 */
class ActionListBuilder
{
    /**
     * @var ActionFactory
     */
    private $actionFactory;

    /**
     * @var array
     */
    private $actions = [];

    /**
     * ActionListBuilder constructor.
     *
     * @param ActionFactory $actionFactory
     */
    public function __construct(ActionFactory $actionFactory)
    {
        $this->actionFactory = $actionFactory;
    }

    /**
     * @param $id
     * @param $actionClass
     * @param array $options
     *
     * @return $this
     */
    public function add($id, $actionClass, array $options = [])
    {
        $this->actions[$id] = [
            'class' => $actionClass,
            'options' => $options,
        ];

        return $this;
    }

    /**
     * @param $id
     *
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
     * @param $id
     *
     * @return Action
     */
    public function get($id)
    {
        return $this->resolveAction($id);
    }

    /**
     * @return array
     */
    public function getActionList()
    {
        $actions = [];
        foreach ($this->actions as $id => $action) {
            $actions[$id] = $this->resolveAction($id);
        }

        return $actions;
    }

    /**
     * @param $id
     * @param false $force
     *
     * @return Action
     */
    protected function resolveAction($id, $force = false)
    {
        if (!isset($this->actions[$id])) {
            throw new \RuntimeException(sprintf('Action with id "%s" does not exist.', $id));
        }

        if (true === $force || !isset($this->actions[$id]['resolved'])) {
            $this->actions[$id]['options']['id'] = $id;
            $this->actions[$id]['resolved'] = $this->actionFactory->create($this->actions[$id]['class'], $this->actions[$id]['options']);
        }

        return $this->actions[$id]['resolved'];
    }
}
