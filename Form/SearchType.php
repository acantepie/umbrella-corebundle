<?php

namespace Umbrella\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Class SearchType
 * @package Umbrella\CoreBundle\Form
 */
class SearchType extends AbstractType
{

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['type'] = 'text';
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => false,
            'required' => false,
            'attr' => [
                'placeholder' => 'form.placeholder.search'
            ]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getParent()
    {
        return TextType::class;
    }
}
