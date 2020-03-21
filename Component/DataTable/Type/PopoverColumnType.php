<?php

namespace Umbrella\CoreBundle\Component\DataTable\Type;


use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\DataTable\Type\PropertyColumnType;

/**
 * Class PopoverColumnType
 * @package CRMBundle\DataTable\Column
 */
class PopoverColumnType extends PropertyColumnType
{
    const POPOVER_TEMPLATE = '<div class="cell-popover" data-toggle="popover" data-placement="%s" data-trigger="%s" data-content="%s" title="%s">%s</div>';

    /**
     * @inheritDoc
     */
    public function render($entity, array $options)
    {
        $cellContent =  call_user_func($options['cell_renderer'], $entity, $options);
        $popoverContent = call_user_func($options['popover_content_renderer'], $entity, $options);
        $popoverTitle = call_user_func($options['popover_title_renderer'], $entity, $options);


        return !empty($popoverContent)
            ? sprintf(
                self::POPOVER_TEMPLATE,
                $options['popover_placement'],
                $options['popover_trigger'],
                htmlspecialchars($popoverContent),
                htmlspecialchars($popoverTitle),
                $cellContent
            )
            : $cellContent;
    }

    /**
     * @param $entity
     * @param array $options
     * @return string
     */
    public function renderCellContent($entity, array $options)
    {
        return (string)$this->accessor->getValue($entity, $options['property_path']);
    }

    /**
     * @param $entity
     * @param array $options
     * @return string
     */
    public function renderPopoverContent($entity, array $options)
    {
        return (string)$this->accessor->getValue($entity, $options['property_path']);
    }

    /**
     * @param $entity
     * @param array $options
     * @return string
     */
    public function renderPopoverTitle($entity, array $options)
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(array(
            'popover_placement' => 'left',
            'popover_trigger' => 'hover',
            'popover_content_renderer' => [$this, 'renderPopoverContent'],
            'popover_title_renderer' => [$this, 'renderPopoverTitle'],
            'cell_renderer' => [$this, 'renderCellContent'],
            'class' => 'popover-cell'
        ));
        $resolver->setAllowedTypes('popover_content_renderer', 'callable');
        $resolver->setAllowedTypes('popover_title_renderer', 'callable');
        $resolver->setAllowedTypes('cell_renderer', 'callable');

    }

}