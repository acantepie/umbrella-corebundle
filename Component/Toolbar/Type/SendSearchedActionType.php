<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 16/04/18
 * Time: 21:12
 */

namespace Umbrella\CoreBundle\Component\Toolbar\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SendSearchedActionType
 */
class SendSearchedActionType extends ActionType
{
    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'attr' => array(
                'data-send' => 'searched'
            ),
            'xhr' => false,
        ));
    }

}