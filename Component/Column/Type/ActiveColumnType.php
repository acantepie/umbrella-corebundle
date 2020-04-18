<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 25/03/20
 * Time: 23:10
 */

namespace Umbrella\CoreBundle\Component\Column\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ActiveColumnType
 */
class ActiveColumnType extends BooleanColumnType
{
    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver
            ->setDefault('yes_value', 'common.enable')
            ->setDefault('no_value', 'common.disabled');
    }

}