<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 17/01/19
 * Time: 23:38
 */

namespace Umbrella\CoreBundle\Component\DataTable\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\DateTime\DateTimeHelper;

/**
 * Class DateDiffColumnType
 */
class DateDiffColumnType extends PropertyColumnType
{
    /**
     * @var DateTimeHelper
     */
    private $dateTimeHelper;

    /**
     * DateDiffColumnType constructor.
     * @param DateTimeHelper $dateTimeHelper
     */
    public function __construct(DateTimeHelper $dateTimeHelper)
    {
        $this->dateTimeHelper = $dateTimeHelper;
        parent::__construct();
    }

    /**
     * @param $entity
     * @param array $options
     * @return mixed|string
     */
    public function render($entity, array $options)
    {
        $value = $this->accessor->getValue($entity, $options['property_path']);
        if ($value instanceof \DateTime) {
            return sprintf(
                '<span data-toggle="tooltip" title="%s">%s</span>',
                $value->format($options['tooltip_format']),
                $this->dateTimeHelper->diff($value, $options['to'])
            );
        } else {
            return $value;
        }
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefault('to', new \DateTime('NOW'));
        $resolver->setDefault('tooltip_format', 'd/m/Y');
        $resolver->setAllowedTypes('tooltip_format', 'string');

        $resolver->setAllowedTypes('to', [\DateTime::class]);
    }
}