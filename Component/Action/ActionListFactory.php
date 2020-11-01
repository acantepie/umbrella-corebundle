<?php

namespace Umbrella\CoreBundle\Component\Action;

/**
 * Class ActionListFactory
 */
class ActionListFactory
{
    /**
     * @var ActionFactory
     */
    private $actionFactory;

    /**
     * ActionListFactory constructor.
     *
     * @param ActionFactory $actionFactory
     */
    public function __construct(ActionFactory $actionFactory)
    {
        $this->actionFactory = $actionFactory;
    }

    /**
     * @return ActionListBuilder
     */
    public function createBuilder()
    {
        return new ActionListBuilder($this->actionFactory);
    }

    /**
     * @return Action
     */
    public function create()
    {
        return $this->createBuilder()->getActionList();
    }
}
