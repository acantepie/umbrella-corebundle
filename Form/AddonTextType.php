<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 23/05/17
 * Time: 21:15.
 */

namespace Umbrella\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AddonTextType.
 */
class AddonTextType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'prefix' => null,
            'suffix' => null,
        ));

        $resolver->setNormalizer('input_prefix', function (Options $options, $value) {
           return $options['prefix'] ? $options['prefix'] : $value;
        });

        $resolver->setNormalizer('input_suffix', function (Options $options, $value) {
            return $options['suffix'] ? $options['suffix'] : $value;
        });
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return TextType::class;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'addontext';
    }
}
