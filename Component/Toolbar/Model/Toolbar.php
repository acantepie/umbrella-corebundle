<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 20/05/17
 * Time: 23:17.
 */

namespace Umbrella\CoreBundle\Component\Toolbar\Model;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Model\OptionsAwareInterface;
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * Class Toolbar.
 */
class Toolbar implements OptionsAwareInterface
{
    /**
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * @var null|array
     */
    protected $formOptions;

    // options

    /**
     * @var array
     */
    public $options;

    /**
     * @var string
     */
    public $template;

    /**
     * @var string
     */
    public $class;

    /**
     * @var string
     */
    public $submitFrom;

    // Model

    /**
     * @var FormInterface|null
     */
    public $form;

    /**
     * @var array
     */
    public $actions;

    /**
     * @param Request $request
     */
    final public function handleRequest(Request $request)
    {
        if ($this->form) {
            $this->form->handleRequest($request);
        }
    }

    /**
     * @return array
     */
    final public function getData()
    {
        return $this->form ? $this->form->getData() : [];
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options = array())
    {
        $this->options = $options;
        
        $this->formOptions = ArrayUtils::get($options, 'form_options');
        $this->template = ArrayUtils::get($options, 'template');
        $this->class = ArrayUtils::get($options, 'class');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(array(
            'form_options',
            'template',
            'class'
        ));

        $resolver->setAllowedTypes('form_options', 'array');

        $resolver->setDefault('form_options', array(
            'validation_groups' => false,
            'csrf_protection' => false,
            'label_class' => 'hidden',
            'group_class' => 'col-md-12',
            'method' => 'GET'
        ));
        $resolver->setDefault('template', '@UmbrellaCore/Toolbar/toolbar.html.twig');
    }

    /* Helper */

    /**
     * @return FormBuilderInterface
     */
    final protected function createFormBuilder()
    {
        return $this->formFactory->createNamedBuilder('toolbar', FormType::class, null, $this->formOptions);
    }
}
