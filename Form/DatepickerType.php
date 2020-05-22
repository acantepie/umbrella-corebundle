<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 05/05/17
 * Time: 23:42.
 */

namespace Umbrella\CoreBundle\Form;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

/**
 * Class DatepickerType.
 */
class DatepickerType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['attr']['data-format'] = $this->toJsFormat($options['format']);

        if ($options['start_date']) {
            $view->vars['attr']['data-date-start-date'] = $options['start_date']->format('d/m/Y');
        }

        if ($options['end_date']) {
            $view->vars['attr']['data-date-end-date'] = $options['end_date']->format('d/m/Y');
        }

        $view->vars['attr']['class'] = isset($view->vars['attr']['class'])
            ? $view->vars['attr']['class'] . ' js-datepicker'
            : 'js-datepicker';

        parent::buildView($view, $form, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'widget' => 'single_text',
            'html5' => false,
            'format' => 'dd/MM/yyyy',
            'start_date' => null,
            'end_date' => null,
        ]);

        $resolver->setAllowedTypes('start_date', [\DateTime::class, 'null']);
        $resolver->setAllowedTypes('end_date', [\DateTime::class, 'null']);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return DateType::class;
    }

    /**
     * @param $format
     * @return string
     */
    private function toJsFormat($format)
    {
        return strtolower($format);
    }
}
