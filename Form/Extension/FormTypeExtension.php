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
        $this->setView($view, $form, 'label_class', $options['label_class']);
        $this->setView($view, $form, 'group_class', $options['group_class']);

        if ($view->vars['label'] !== false) {
            if (empty($view->vars['label'])) {
                $view->vars['label'] = $view->vars['name'];
            }

            if ($options['translation_domain'] !== false) {
                $view->vars['label'] = $options['label_prefix'] . $view->vars['label'];
            }
        }
        if (isset($options['header'])) {
            $view->vars['header'] = $options['header'];
        }

        $view->vars['input_prefix'] = $options['input_prefix'];
        $view->vars['input_suffix'] = $options['input_suffix'];
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('label_class', 'col-sm-2')
            ->setAllowedTypes('label_class', ['string', 'null'])

            ->setDefault('group_class', 'col-sm-10')
            ->setAllowedTypes('group_class', ['string', 'null'])

            ->setDefault('label_prefix', 'form.label.')
            ->setAllowedTypes('label_prefix', ['null', 'string'])

            ->setDefault('header', null)
            ->setAllowedTypes('header', ['null', 'string'])

            ->setDefault('input_prefix', null)
            ->setAllowedTypes('input_prefix', ['null', 'string'])

            ->setDefault('input_suffix', null)
            ->setAllowedTypes('input_suffix', ['null', 'string']);
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
     * @param FormView $view
     * @param FormInterface $form
     * @param $attributeName
     * @param $attributeValue
     */
    protected function setView(FormView $view, FormInterface $form, $attributeName, $attributeValue)
    {
        if ($attributeValue !== null) {
            $view->vars[$attributeName] = $attributeValue;
        } elseif ($form->getRoot()->getConfig()->hasAttribute($attributeName)) { // else if root has attribute defined -> set it to view
            $view->vars[$attributeName] = $form->getRoot()->getConfig()->getAttribute($attributeName);
        }
    }
}
