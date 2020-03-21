<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 20/05/17
 * Time: 09:45.
 */

namespace Umbrella\CoreBundle\Component\DataTable\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class BooleanColumnType.
 */
class BooleanColumnType extends PropertyColumnType
{
    /**
     * @param $entity
     * @param array $options
     * @return string
     */
    public function render($entity, array $options)
    {
        switch ($this->accessor->getValue($entity, $options['property_path'])) {
            case true:
                return $options['true'];

            case false:
                return $options['false'];

            default:
                return $options['null'];
        }
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(array(
            'true' => '<i class="fa fa-check text-success"></i>',
            'false' => '<i class="fa fa-ban text-danger"></i>',
            'null' => ''
        ));
    }
}
