<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 20/05/17
 * Time: 23:17.
 */

namespace Umbrella\CoreBundle\Component\Toolbar;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Umbrella\CoreBundle\Component\ComponentView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Toolbar.
 */
class Toolbar
{
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
     * @var array
     */
    private $options;

    /**
     * @param Request $request
     */
    final public function handleRequest(Request $request)
    {
        $this->form->handleRequest($request);
    }

    /**
     * @return array
     */
    final public function getFormData()
    {
        return (array) $this->form->getData();
    }

    /**
     * @inheritdoc
     */
    public function setOptions(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('toolbar_form_name', 'toolbar_form')

            ->setDefault('toolbar_form_options', [
                'validation_groups' => false,
                'csrf_protection' => false,
                'label' => false,
                'required' => false,
                'label_class' => 'hidden',
                'group_class' => 'col-sm-12',
                'method' => 'GET'
            ])
            ->setAllowedTypes('toolbar_form_options', 'array')

            ->setDefault('toolbar_template', '@UmbrellaCore/Toolbar/toolbar.html.twig')
            ->setAllowedTypes('toolbar_template', 'string')

            ->setDefault('toolbar_form_data', null);
    }

    /**
     * @return ComponentView
     */
    public function createView() : ComponentView
    {
        $view = new ComponentView();
        $view->template = $this->options['toolbar_template'];
        $view->vars['form'] = $this->form->createView();
        $view->vars['actions'] = $this->actions;

        return $view;
    }
}
