<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 20/10/17
 * Time: 20:09
 */

namespace Umbrella\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

/**
 * Class OrderType
 */
class OrderType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function getParent()
    {
        return HiddenType::class;
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => false,
            'attr' => [
                'class' => 'js-order'
            ]
        ]);
    }
}
