<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 18:51.
 */

namespace Umbrella\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

// see AsyncEntity2Type to work asynchronously with entity

/* -- How template result ?

Choice2Type use mustache for templating, see https://mustache.github.io/

'id' and 'text' value are always pass to mustache template
you can add 'extra value with 'choice_attr' options

$builder->add('user', Entity2Type::class, array(
    'choice_attr' => function($user) {
        return array(
            'data-email' => $user->email
        );
    },
    'template_html' => '<b>[[text]]</b><br>[[data.email]]',
    ...
));

to avoid set template on FormType, you can use 'template_selector' options:

$builder->add('user', Entity2Type::class, array(
    'choice_attr' => function($user) {
        return array(
            'data->email' => $user->email
        );
    },
    'template_selector' => '#tpl'
    ...
));

template must exist on html view :

<template id="tpl">
    <b>[[text]]</b><br>[[data.email]]
</template>

*/

/* -- How load result asynchronously ?

use AsyncEntity2Type class

Load choices asynchronously does not work well with ChoiceType/EntityType symfony form.
FormType has to maintain set of entities/choices to map selected value but this set is empty on asynchronous mode.

*/

/**
 * Class Choice2Type.
 */
class Choice2Type extends AbstractType
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * Choice2Type constructor.
     * @param TranslatorInterface $translator
     * @param RouterInterface $router
     */
    public function __construct(TranslatorInterface $translator, RouterInterface $router)
    {
        $this->translator = $translator;
        $this->router = $router;
    }

    /**
     * @inheritdoc
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['attr']['data-options'] = htmlspecialchars(json_encode($this->buildJsOptions($view, $form, $options)));

        // avoid use some values
        $view->vars['placeholder'] = $view->vars['placeholder'] === null ? null : '';
        $view->vars['expanded'] = false;

        if (isset($view->vars['attr']['class'])) {
            $view->vars['attr']['class'] .=  ' js-select2';
        } else {
            $view->vars['attr']['class'] = 'js-select2';
        }
    }

    protected function buildJsOptions(FormView $view, FormInterface $form, array $options)
    {
        // select2 Options
        $jsSelect2Options = $options['select2_options'];
        $jsSelect2Options['language'] = $options['language'];

        $jsSelect2Options['placeholder'] = empty($options['placeholder'])
            ? $options['placeholder']
            : $this->translator->trans($options['placeholder']);

        $jsSelect2Options['allowClear'] = $view->vars['required'] !== true; // allow clear if not required
        $jsSelect2Options['minimumInputLength'] = $options['min_search_length'];
        $jsSelect2Options['width'] = $options['width'];

        // js Options
        $jsOptions = array();
        $jsOptions['template_selector'] = $options['template_selector'];
        $jsOptions['template_html'] = $options['template_html'];

        if (!empty($options['route'])) {
            $jsOptions['ajax_url'] = $this->router->generate($options['route'], $options['route_params']);
        }

        $jsOptions['select2'] = $jsSelect2Options;

        return $jsOptions;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'route' => null,
            'route_params' => [],

            'language' => 'fr',
            'min_search_length' => 0,
            'width' => 'auto',

            'template_selector' => null,
            'template_html' => null,

            'select2_options' => [],
        ));

        $resolver->setAllowedTypes('route', ['null', 'string']);
        $resolver->setAllowedTypes('route_params', 'array');

        $resolver->setAllowedTypes('min_search_length', 'int');
        $resolver->setAllowedTypes('language', 'string');
        $resolver->setAllowedTypes('width', ['null', 'string']);

        $resolver->setAllowedTypes('template_selector', ['null', 'string']);
        $resolver->setAllowedTypes('template_html', ['null', 'string']);
        $resolver->setAllowedTypes('select2_options', ['array']);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ChoiceType::class;
    }
}
