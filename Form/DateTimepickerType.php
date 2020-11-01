<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 05/05/17
 * Time: 23:42.
 */

namespace Umbrella\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DatepickerType.
 */
class DateTimepickerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['attr']['data-format'] = $options['js_format'];
        $view->vars['attr']['data-show-clear'] = $options['show_clear'] ? 'true' : 'false';

        if ($options['start_date']) {
            $view->vars['attr']['data-min-date'] = $options['start_date']->format('Y-m-d H:i');
        }

        if ($options['end_date']) {
            $view->vars['attr']['data-max-date'] = $options['end_date']->format('Y-m-d H:i');
        }

        $view->vars['attr']['class'] = isset($view->vars['attr']['class'])
            ? $view->vars['attr']['class'] . ' js-datetimepicker'
            : 'js-datetimepicker';

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
            'format' => 'dd/MM/yyyy HH:mm',
            'js_format' => 'DD/MM/YYYY HH:mm',
            'show_clear' => false,
            'start_date' => null,
            'end_date' => null,
        ]);

        $resolver->setAllowedTypes('show_clear', 'boolean');

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
}
