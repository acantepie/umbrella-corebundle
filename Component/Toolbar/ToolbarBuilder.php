<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 26/03/20
 * Time: 22:19
 */

namespace Umbrella\CoreBundle\Component\Toolbar;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Umbrella\CoreBundle\Component\Action\Action;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Umbrella\CoreBundle\Component\Action\Type\ActionType;
use Umbrella\CoreBundle\Component\Action\ActionListFactory;

/**
 * Class ToolbarBuilder
 */
class ToolbarBuilder
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var FormBuilderInterface
     */
    private $formBuilder;

    /**
     * @var ActionListFactory
     */
    private $actionListFactory;

    /**
     * @var ActionListBuilder
     */
    private $actionListBuilder;

    /**
     * @var ToolbarAwareTypeInterface
     */
    private $awareType;

    /**
     * ToolbarBuilder constructor.
     *
     * @param FormFactoryInterface      $formFactory
     * @param ActionListFactory         $actionListFactory
     * @param ToolbarAwareTypeInterface $awareType
     */
    public function __construct(FormFactoryInterface $formFactory, ActionListFactory $actionListFactory, ToolbarAwareTypeInterface $awareType)
    {
        $this->formFactory = $formFactory;
        $this->actionListFactory = $actionListFactory;
        $this->awareType = $awareType;
    }

    // Inherit from filter builder

    /**
     * @param $child
     * @param  null  $type
     * @param  array $options
     * @return $this
     */
    public function addFilter($child, $type = null, array $options = [])
    {
        $this->formBuilder->add($child, $type, $options);
        return $this;
    }

    /**
     * @param $name
     * @return FormBuilderInterface
     */
    public function getFilter($name)
    {
        return $this->formBuilder->get($name);
    }

    /**
     * @param $name
     * @return $this
     */
    public function removeFilter($name)
    {
        $this->formBuilder->remove($name);
        return $this;
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasFilter($name)
    {
        return $this->formBuilder->has($name);
    }

    // Action builder

    /**
     * @param $id
     * @param  string $type
     * @param  array  $options
     * @return $this
     */
    public function addAction($id, $type = ActionType::class, array $options = [])
    {
        $this->actionListBuilder->add($id, $type, $options);
        return $this;
    }

    /**
     * @param $id
     * @return $this
     */
    public function removeAction($id)
    {
        $this->actionListBuilder->remove($id);
        return $this;
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function hasAction($id)
    {
        return $this->actionListBuilder->has($id);
    }

    /**
     * @param $id
     * @return Action
     */
    public function getAction($id)
    {
        return $this->actionListBuilder->get($id);
    }

    /**
     * @param  array   $resolvedOptions
     * @return Toolbar
     */
    public function getToolbar(array $resolvedOptions = [])
    {
        $toolbar = new Toolbar();

        $this->formBuilder = $this->formFactory->createNamedBuilder($resolvedOptions['toolbar_form_name'], FormType::class, $resolvedOptions['toolbar_form_data'], $resolvedOptions['toolbar_form_options']);
        $this->actionListBuilder = $this->actionListFactory->createBuilder();

        // options are already resolved at this point
        $this->awareType->buildToolbar($this, $resolvedOptions);
        $toolbar->setOptions($resolvedOptions);
        $toolbar->form = $this->formBuilder->getForm();
        $toolbar->actions = $this->actionListBuilder->getActionList();

        return $toolbar;
    }
}
