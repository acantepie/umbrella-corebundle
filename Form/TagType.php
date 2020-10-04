<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 04/07/17
 * Time: 22:28
 */

namespace Umbrella\CoreBundle\Form;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Class TagType
 */
class TagType extends AbstractType
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (isset($view->vars['attr']['class'])) {
            $view->vars['attr']['class'] .= ' js-tag';
        } else {
            $view->vars['attr']['class'] = 'js-tag';
        }
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new TagTransformer());
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return TextType::class;
    }
}

/**
 * Class TagTransformer
 */
class TagTransformer implements DataTransformerInterface
{
    const SEP = ',';

    /**
     * Transform array => string
     * @param  array  $tags
     * @return string
     */
    public function transform($tags)
    {
        return is_array($tags) ? implode(self::SEP, $tags) : '';
    }

    /**
     * Transform string => array
     *
     * @param  string     $data
     * @return array|null
     */
    public function reverseTransform($data)
    {
        return array_filter(explode(self::SEP, $data));
    }
}
