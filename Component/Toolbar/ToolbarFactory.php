<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 23/05/17
 * Time: 20:03.
 */

namespace Umbrella\CoreBundle\Component\Toolbar;

use Symfony\Component\Form\FormFactoryInterface;
use Umbrella\CoreBundle\Component\Action\ActionListFactory;

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
     * @var ActionListFactory
     */
    private $actionListFactory;

    /**
     * ToolbarFactory constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param ActionListFactory    $actionListFactory
     */
    public function __construct(FormFactoryInterface $formFactory, ActionListFactory $actionListFactory)
    {
        $this->formFactory = $formFactory;
        $this->actionListFactory = $actionListFactory;
    }

    /**
     * @param ToolbarAwareTypeInterface $awareType
     * @param array                     $options
     *
     * @return Toolbar
     */
    public function create(ToolbarAwareTypeInterface $awareType, array $options = [])
    {
        return $this->createBuilder($awareType)->getToolbar($options);
    }

    /**
     * @param ToolbarAwareTypeInterface $awareType
     *
     * @return ToolbarBuilder
     */
    public function createBuilder(ToolbarAwareTypeInterface $awareType)
    {
        return new ToolbarBuilder($this->formFactory, $this->actionListFactory, $awareType);
    }
}
