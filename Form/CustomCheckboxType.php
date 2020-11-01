<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 22/03/20
 * Time: 16:59
 */

namespace Umbrella\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CustomCheckboxType
 */
class CustomCheckboxType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label_attr' => [
                'class' => 'checkbox-custom',
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return CheckboxType::class;
    }
}
