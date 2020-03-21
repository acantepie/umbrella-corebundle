<?php

namespace Umbrella\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IntervalType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new IntervalTransformer());
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['attr']['data-options'] = htmlspecialchars(json_encode($this->buildJsOptions($view, $form, $options)));
    }

    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     * @return array
     */
    protected function buildJsOptions(FormView $view, FormInterface $form, array $options)
    {
        $jsOptions = array();

        if (isset($options['step']) && !empty($options['step'])) {
            $jsOptions['step'] = $options['step'];
        }

        $jsOptions['min'] = $options['min'];
        $jsOptions['max'] = $options['max'];
        $jsOptions['type'] = $options['type'];

        if (isset($options['prefix']) && !empty($options['prefix'])) {
            $jsOptions['prefix'] = $options['prefix'];
        }

        if (isset($options['suffix']) && !empty($options['suffix'])) {
            $jsOptions['suffix'] = $options['suffix'];
        }

        return $jsOptions;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(array(
            'step',
            'prefix',
            'suffix'
        ));

        $resolver->setDefaults(array(
            'min' => 0,
            'max' => 10000,
            'type' => 'integer',
        ));
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return TextType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'interval';
    }

}

/**
 * Class TagTransformer
 */
class IntervalTransformer implements DataTransformerInterface
{

    /**
     * Transform array => string
     * @param array $tags
     * @return string
     */
    public function transform($json)
    {
        return is_array($json) ? implode(',', $json) : '';
    }

    /**
     * Transform string => array
     *
     * @param string $data
     * @return array|null
     */
    public function reverseTransform($data)
    {
        return array_filter(explode(',', $data));
    }
}