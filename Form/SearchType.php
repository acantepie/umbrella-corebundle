<?php


namespace Umbrella\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Utils\HtmlUtils;

/**
 * Class SearchType
 * @package Umbrella\CoreBundle\Form
 */
class SearchType extends AbstractType
{

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'widget' => 'single_text',
            'label' => false,
            'input_suffix' => HtmlUtils::render_icon('mdi mdi-magnify'),
            'required' => false,
            'attr' => array(
                'placeholder' => 'form.placeholder.search'
            )
        ));
    }

    /**
     * @inheritdoc
     */
    public function getParent()
    {
        return TextType::class;
    }


}