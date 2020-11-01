<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 19/04/20
 * Time: 16:29
 */

namespace Umbrella\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TitleType
 */
class TitleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('mapped', false);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'umbrellatitle';
    }
}
