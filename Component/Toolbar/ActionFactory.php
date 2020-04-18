<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 16/04/18
 * Time: 19:41
 */

namespace Umbrella\CoreBundle\Component\Toolbar;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\Toolbar\Action\Action;
use Umbrella\CoreBundle\Component\Toolbar\Action\ActionType;

/**
 * Class ActionFactory
 */
class ActionFactory
{
    /**
     * @var ActionType[]
     */
    private $actionTypes = array();

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
    public function create($typeClass, array $options = array())
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