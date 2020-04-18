<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 16/04/18
 * Time: 19:30
 */

namespace Umbrella\CoreBundle\Component\Column\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Utils\StringUtils;

/**
 * Class CheckBoxColumnType
 */
class CheckBoxColumnType extends ColumnType
{
    const CHECKBOX_TPL = '<div class="custom-control custom-checkbox"><input type="checkbox" id="cb-%s" class="custom-control-input"><label class="checkbox-custom custom-control-label" for="cb-%s"></label></div>';

    /**
     * @param $entity
     * @param array $options
     * @return string
     */
    public function render($entity, array $options)
    {
        $htmlId = StringUtils::random(8);
        return sprintf(self::CHECKBOX_TPL, $htmlId, $htmlId);
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $htmlId = StringUtils::random(8);

        $resolver
            ->setDefault('order_by', null)
            ->setDefault('class', 'text-center disable-row-click js-select')
            ->setDefault('renderer', [$this, 'render'])
            ->setDefault('label', sprintf(self::CHECKBOX_TPL, $htmlId, $htmlId))
            ->setDefault('label_prefix', null)
            ->setDefault('translation_domain', null)
            ->setDefault('width', '80px');
    }
}