<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 20/05/17
 * Time: 23:17.
 */

namespace Umbrella\CoreBundle\Component\Toolbar\Model;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\Source\CallbackSourceModifier;
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
     * @var CallbackSourceModifier[]
     */
    public $sourceModifiers;

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
            ->setDefault('form_options', array(
                'validation_groups' => false,
                'csrf_protection' => false,
                'label' => false,
                'required' => false,
                'method' => 'GET'
            ))
            ->setAllowedTypes('form_options', 'array')

            ->setDefault('template', '@UmbrellaCore/Toolbar/toolbar.html.twig')
            ->setAllowedTypes('template', 'string')

            ->setDefault('form_data', null);
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->options['template'];
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
