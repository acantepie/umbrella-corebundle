<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 27/06/17
 * Time: 22:29
 */

namespace Umbrella\CoreBundle\Component\Toolbar\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AddActionType
 */
class AddActionType extends ActionType
{
    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('icon', 'add');
        $resolver->setDefault('class', 'btn btn-primary');
    }
}