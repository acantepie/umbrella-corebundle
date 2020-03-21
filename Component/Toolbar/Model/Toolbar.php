<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 20/05/17
 * Time: 23:17.
 */

namespace Umbrella\CoreBundle\Component\Toolbar\Model;

use Doctrine\ORM\QueryBuilder;
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
    const SUBMIT_MANUALLY = 'MANUALLY'; // submit form manually
    const SUBMIT_ONCHANGE = 'ONCHANGE'; // submit form after a change

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
     * @var \Closure|null
     */
    public $queryClosure;

    /**
     * @param Request      $request
     */
    final public function handleRequest(Request $request)
    {
        if ($this->form) {
            $this->form->handleRequest($request);
        }
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
        $this->submitFrom = ArrayUtils::get($options, 'submit_form');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(array(
            'submit_form',
            'form_options',
            'template',
            'class'
        ));

        $resolver->setAllowedTypes('form_options', 'array');
        $resolver->setAllowedValues('submit_form', [self::SUBMIT_MANUALLY, self::SUBMIT_ONCHANGE]);

        $resolver->setDefault('form_options', array(
            'validation_groups' => false,
            'csrf_protection' => false,
            'label_class' => 'hidden',
            'group_class' => 'col-md-12',
            'method' => 'GET'
        ));
        $resolver->setDefault('template', '@UmbrellaCore/Toolbar/toolbar.html.twig');
        $resolver->setDefault('submit_form', self::SUBMIT_ONCHANGE);
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
