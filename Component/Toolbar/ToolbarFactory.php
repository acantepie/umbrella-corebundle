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
use Umbrella\CoreBundle\Component\Toolbar\Type\ToolbarAwareTypeInterface;

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
     * @param ToolbarAwareTypeInterface $awareType
     * @param array $options
     * @return Toolbar
     */
    public function create(ToolbarAwareTypeInterface $awareType, array $options = array())
    {
        return $this->createBuilder($awareType)->getToolbar($options);
    }

    /**
     * @param ToolbarAwareTypeInterface $awareType
     * @return ToolbarBuilder
     */
    public function createBuilder(ToolbarAwareTypeInterface $awareType)
    {
        return new ToolbarBuilder($this->formFactory, $this->actionFactory, $awareType);
    }
}
