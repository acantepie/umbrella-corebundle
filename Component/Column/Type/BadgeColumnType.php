<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 12/02/18
 * Time: 10:37
 */

namespace Umbrella\CoreBundle\Component\Column\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class BadgeColumnType
 */
class BadgeColumnType extends PropertyColumnType
{
    /**
     * @param $entity
     * @param  array  $options
     * @return string
     */
    public function render($entity, array $options)
    {
        $value = $this->accessor->getValue($entity, $options['property_path']);
        return sprintf('<span class="badge %s">%s<span>', $options['badge_class'], $value);
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver
            ->setDefault('badge_class', 'badge-primary')
            ->setAllowedTypes('badge_class', ['null', 'string']);
    }
}
