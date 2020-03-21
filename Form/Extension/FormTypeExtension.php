<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 29/05/17
 * Time: 19:49.
 */

namespace Umbrella\CoreBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FormTypeExtension.
 */
class FormTypeExtension extends AbstractTypeExtension
{
    /**
     * @param FormView      $view
     * @param FormInterface $form
     * @param array         $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $this->setView($view, $form, 'label_class', 'col-md-3');
        $this->setView($view, $form, 'group_class', 'col-md-8');
        $this->setView($view, $form, 'row_class', '');

        if ($view->vars['label'] !== false) {
            if (empty($view->vars['label'])) {
                $view->vars['label'] = $view->vars['name'];
            }

            if ($options['translation_domain'] !== false) {
                $view->vars['label'] = $options['label_prefix'] . $view->vars['label'];
            }
        }

        if (isset($options['help'])) {
            $view->vars['help'] = $options['help'];
        }
        if (isset($options['help_class'])) {
            $view->vars['help_class'] = $options['help_class'];
        }
        if (isset($options['header'])) {
            $view->vars['header'] = $options['header'];
        }

        $view->vars['input_prefix'] = $options['input_prefix'];
        $view->vars['input_suffix'] = $options['input_suffix'];
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setAttribute($builder, $options, 'label_class');
        $this->setAttribute($builder, $options, 'group_class');
        $this->setAttribute($builder, $options, 'row_class');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(array(
            'label_class',
            'group_class',
            'row_class',
            'help',
            'help_class',
            'header',
            'input_prefix',
            'input_suffix'
        ));

        $resolver->setDefault('label_prefix', 'form.label.');
        $resolver->setDefault('input_prefix', null);
        $resolver->setDefault('input_suffix', null);

        $resolver->setAllowedTypes('input_prefix', ['string', 'null']);
        $resolver->setAllowedTypes('input_suffix', ['string', 'null']);
    }

    /**
     * @inheritdoc
     */
    public static function getExtendedTypes(): iterable
    {
        return [FormType::class];
    }

    /* Helper */

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     * @param $optionName
     */
    protected function setAttribute(FormBuilderInterface $builder, array $options, $optionName)
    {
        if (isset($options[$optionName])) {
            $builder->setAttribute($optionName, $options[$optionName]);
        }
    }

    /**
     * @param FormView      $view
     * @param FormInterface $form
     * @param $attributeName
     * @param $defaultValue
     */
    protected function setView(FormView $view, FormInterface $form, $attributeName, $defaultValue)
    {
        if ($form->getConfig()->hasAttribute($attributeName)) { // if attribute is defined -> set it to view
            $view->vars[$attributeName] = $form->getConfig()->getAttribute($attributeName);
        } elseif ($form->getRoot()->getConfig()->hasAttribute($attributeName)) { // else if root has attribute defined -> set it to view
            $view->vars[$attributeName] = $form->getRoot()->getConfig()->getAttribute($attributeName);
        } else { // else set default value to view
            $view->vars[$attributeName] = $defaultValue;
        }
    }
}
