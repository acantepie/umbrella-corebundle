<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 23/05/17
 * Time: 20:03.
 */

namespace Umbrella\CoreBundle\Component\Toolbar;

use Symfony\Component\Form\FormFactoryInterface;
use Umbrella\CoreBundle\Component\Toolbar\Model\Toolbar;
use Umbrella\CoreBundle\Component\Toolbar\Type\ToolbarType;

/**
 * Class ToolbarFactory.
 */
class ToolbarFactory
{
    /**
     * @var FormFactoryInterface
     */
   private $formFactory;

    /**
     * @var ActionFactory
     */
   private $actionFactory;

    /**
     * @var ToolbarType[]
     */
   private $toolbarTypes = array();

    /**
     * ToolbarFactory constructor.
     * @param FormFactoryInterface $formFactory
     * @param ActionFactory $actionFactory
     */
    public function __construct(FormFactoryInterface $formFactory, ActionFactory $actionFactory)
    {
        $this->formFactory = $formFactory;
        $this->actionFactory = $actionFactory;
    }

    /**
     * @param $id
     * @param ToolbarType $toolbarType
     */
    public function registerToolbarType($id, ToolbarType $toolbarType)
    {
        $this->toolbarTypes[$id] = $toolbarType;
    }

    /**
     * @param $typeClass
     * @param array $options
     *
     * @return Toolbar
     */
    public function create($typeClass, array $options = array())
    {
        return $this->createBuilder($typeClass, $options)->getToolbar();
    }

    /**
     * @param string $typeClass
     * @param array  $options
     *
     * @return ToolbarBuilder
     */
    public function createBuilder($typeClass = ToolbarType::class, array $options = array())
    {
        return new ToolbarBuilder($this->formFactory, $this->actionFactory, $this->createType($typeClass), $options);
    }

    /**
     * @param $typeClass
     * @return ToolbarType
     */
    private function createType($typeClass)
    {
        if ($typeClass !== ToolbarType::class && !is_subclass_of($typeClass, ToolbarType::class)) {
            throw new \InvalidArgumentException("Class '$typeClass' must extends ToolbarType class.");
        }

        if (array_key_exists($typeClass, $this->toolbarTypes)) {
            return $this->toolbarTypes[$typeClass];
        } else {
            return new $typeClass();
        }
    }
}
