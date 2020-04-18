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
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Model\OptionsAwareInterface;

/**
 * Class Toolbar.
 */
class Toolbar implements OptionsAwareInterface
{
    /**
     * @var array
     */
    private $options;

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
        return $this->form ? (array) $this->form->getData() : [];
    }

    /**
     * @inheritdoc
     */
    public function setOptions(array $options = array())
    {
        $this->options = $options;
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('toolbar_form_options', array(
                'validation_groups' => false,
                'csrf_protection' => false,
                'label' => false,
                'required' => false,
                'label_class' => 'hidden',
                'group_class' => 'col-sm-12',
                'method' => 'GET'
            ))
            ->setAllowedTypes('toolbar_form_options', 'array')

            ->setDefault('toolbar_template', '@UmbrellaCore/Toolbar/toolbar.html.twig')
            ->setAllowedTypes('toolbar_template', 'string')

            ->setDefault('toolbar_form_data', null);
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->options['toolbar_template'];
    }

    /**
     * @return array
     */
    public function getViewOptions()
    {
        return array(
            'form' => $this->form ? $this->form->createView() : null,
            'actions' => $this->actions
        );
    }
}