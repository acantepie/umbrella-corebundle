<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 16/04/18
 * Time: 21:12
 */

namespace Umbrella\CoreBundle\Component\Toolbar\Action;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SendSelectedActionType
 */
class SendSelectedActionType extends ActionType
{
    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => [
                'data-send' => 'selected'
            ],
            'xhr' => false,
        ]);
    }
}
