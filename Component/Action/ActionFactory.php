<?php

namespace Umbrella\CoreBundle\Component\Action;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\Action\Type\ActionType;

/**
 * Class ActionFactory
 */
class ActionFactory
{
    /**
     * @var ActionType[]
     */
    private $actionTypes = [];

    /**
     * @param $id
     * @param ActionType $actionType
     */
    public function registerActionType($id, ActionType $actionType)
    {
        $this->actionTypes[$id] = $actionType;
    }

    /**
     * @param $typeClass
     * @param array $options
     *
     * @return Action
     */
    public function create($typeClass, array $options = [])
    {
        $type = $this->createType($typeClass);
        $action = new Action();

        $resolver = new OptionsResolver();
        $action->configureOptions($resolver);
        $type->configureOptions($resolver);
        $resolvedOptions = $resolver->resolve($options);
        $action->setOptions($resolvedOptions);

        return $action;
    }

    /**
     * @param $typeClass
     * @return ActionType
     */
    private function createType($typeClass)
    {
        if ($typeClass !== ActionType::class && !is_subclass_of($typeClass, ActionType::class)) {
            throw new \InvalidArgumentException("Class '$typeClass' must extends ActionType class.");
        }

        if (array_key_exists($typeClass, $this->actionTypes)) {
            return $this->actionTypes[$typeClass];
        } else {
            return new $typeClass();
        }
    }
}
