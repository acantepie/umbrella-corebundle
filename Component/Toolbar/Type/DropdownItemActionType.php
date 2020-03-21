<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 23/04/18
 * Time: 16:35
 */

namespace Umbrella\CoreBundle\Component\Toolbar\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DropdownItemType
 */
class DropdownItemActionType extends ActionType
{
    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'class' => 'dropdown-item'
        ));
    }
}