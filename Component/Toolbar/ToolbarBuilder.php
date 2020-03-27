<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 26/03/20
 * Time: 22:19
 */

namespace Umbrella\CoreBundle\Component\Toolbar;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\Source\SourceModifier;
use Umbrella\CoreBundle\Component\Toolbar\Model\Action;
use Umbrella\CoreBundle\Component\Toolbar\Model\Toolbar;
use Umbrella\CoreBundle\Component\Toolbar\Type\ToolbarType;

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
     * @var ActionFactory
     */
    private $actionFactory;

    /**
     * @var ToolbarType
     */
    private $type;

    /**
     * @var array
     */
    private $options = array();

    /**
     * @var array
     */
    private $actions = array();

    /**
     * @var SourceModifier[]
     */
    private $sourceModifiers = array();

    /**
     * ToolbarBuilder constructor.
     * @param FormFactoryInterface $formFactory
     * @param ActionFactory $actionFactory
     * @param ToolbarType $type
     * @param array $options
     */
    public function __construct(FormFactoryInterface $formFactory, ActionFactory $actionFactory, ToolbarType $type, array $options)
    {
        $this->formFactory = $formFactory;
        $this->actionFactory = $actionFactory;
        $this->type = $type;
        $this->options = $options;
    }

    // Inherit from filter builder

    /**
     * @param $child
     * @param null $type
     * @param array $options
     * @return FormBuilderInterface
     */
    public function addFilter($child, $type = null, array $options = [])
    {
        return $this->formBuilder->add($child, $type, $options);
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
     */
    public function removeFilter($name)
    {
        $this->formBuilder->remove($name);
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasFilter($name)
    {
        return $this->formBuilder->has($name);
    }

    /**
     * @return array
     */
    public function allFilter()
    {
        return $this->formBuilder->all();
    }

    // Action builder

    /**
     * @param $id
     * @param $actionClass
     * @param array $options
     *
     * @return $this
     */
    public function addAction($id, $actionClass, array $options = array())
    {
        $this->actions[$id] = array(
            'class' => $actionClass,
            'options' => $options,
        );

        return $this;
    }

    /**
     * @param $id
     * @return $this
     */
    public function removeAction($id)
    {
        unset($this->actions[$id]);
        return $this;
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function hasAction($id)
    {
        return isset($this->actions[$id]);
    }

    /**
     * @param $id
     * @return Action
     * @throws \Exception
     */
    public function getAction($id)
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
     *
     */
    protected function resolveActions()
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
        $action = $this->actions[$id];
        $action['options']['id'] = $id;
        $this->actions[$id]['resolved'] = $this->actionFactory->create($action['class'], $action['options']);
    }

    // Source modifier

    /**
     * @param $callback
     * @param int $priority
     */
    public function addSourceModifier($callback , $priority = 0)
    {
        $this->sourceModifiers[] = new SourceModifier($callback, $priority);
    }

    /**
     *
     */
    public function clearSourceModifiers()
    {
        $this->sourceModifiers = [];
    }

    /**
     * @return Toolbar
     */
    public function getToolbar()
    {
        $toolbar = new Toolbar();

        // resolve options
        $resolver = new OptionsResolver();
        $toolbar->configureOptions($resolver);
        $this->type->configureOptions($resolver);
        $resolvedOptions = $resolver->resolve($this->options);

        $this->formBuilder = $this->formFactory->createBuilder(FormType::class, $resolvedOptions['form_data'], $resolvedOptions['form_options']);
        $this->type->buildToolbar($this, $resolvedOptions);
        $toolbar->setOptions($resolvedOptions);

        // resolve actions
        $this->resolveActions();
        foreach ($this->actions as $arg) {
            $toolbar->actions[] = $arg['resolved'];
        }

        // resolve form
        $toolbar->form = $this->formBuilder->getForm();

        // resolve source modifiers
        $toolbar->sourceModifiers = $this->sourceModifiers;

        return $toolbar;
    }
}