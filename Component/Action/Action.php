<?php

namespace Umbrella\CoreBundle\Component\Action;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Umbrella\CoreBundle\Component\Action\Type\ActionType;
use Umbrella\CoreBundle\Component\ComponentView;

/**
 * Class Action
 */
class Action
{
    const DATA_DATATABLE_FILTER = 'dt_filter';
    const DATA_DATATABLE_SELECTION = 'dt_selection';

    /**
     * @var ActionType
     */
    private $type;

    /**
     * @var array
     */
    private $options;

    /**
     * @param ActionType $type
     */
    public function setType(ActionType $type)
    {
        $this->type = $type;
    }

    public function setOptions(array $options = [])
    {
        $this->options = $options;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('id')
            ->setAllowedTypes('id', 'string')

            ->setDefault('label', function (Options $options) {
                return $options['id'];
            })
            ->setAllowedTypes('label', 'string')

            ->setDefault('label_prefix', 'action.')
            ->setAllowedTypes('label_prefix', ['null', 'string'])

            ->setDefault('translation_domain', 'messages')
            ->setAllowedTypes('translation_domain', ['null', 'string'])

            ->setDefault('template', '@UmbrellaCore/Action/action.html.twig')
            ->setAllowedTypes('template', 'string')

            ->setDefault('xhr', false)
            ->setAllowedTypes('xhr', 'bool')

            ->setDefault('route', null)
            ->setAllowedTypes('route', ['null', 'string'])

            ->setDefault('route_params', [])
            ->setAllowedTypes('route_params', 'array')

            ->setDefault('url', null)
            ->setAllowedTypes('url', ['string', 'null'])

            ->setDefault('confirm', null)
            ->setAllowedTypes('confirm', ['null', 'string'])

            ->setDefault('spinner', false)
            ->setAllowedTypes('spinner', 'bool')

            ->setDefault('xhr_id', null)
            ->setAllowedTypes('xhr_id', ['null', 'string'])

            ->setDefault('class', null)
            ->setAllowedTypes('class', ['null', 'string'])

            ->setDefault('icon', null)
            ->setAllowedTypes('icon', ['null', 'string'])

            ->setDefault('extra_data', null)
            ->setAllowedTypes('extra_data', ['null', 'string'])
            ->setAllowedValues('extra_data', [null, self::DATA_DATATABLE_SELECTION, self::DATA_DATATABLE_FILTER]);
    }

    /**
     * @param RouterInterface     $router
     * @param TranslatorInterface $translator
     *
     * @return ComponentView
     */
    public function createView(RouterInterface $router, TranslatorInterface $translator): ComponentView
    {
        $view = new ComponentView();
        $view->template = $this->options['template'];

        if (!empty($this->options['icon'])) {
            $view->vars['icon'] = $this->options['icon'];

            if (!empty($this->options['label'])) {
                $view->vars['icon'] .= ' mr-1';
            }
        } else {
            $view->vars['icon'] = null;
        }

        $view->vars['label_prefix'] = $this->options['label_prefix'];
        $view->vars['label'] = $this->options['label'];
        $view->vars['translation_domain'] = $this->options['translation_domain'];

        if ($this->options['route']) {
            $url = $router->generate($this->options['route'], $this->options['route_params']);
        } else {
            $url = $this->options['url'];
        }

        if ($this->options['xhr']) {
            $view->vars['attr']['data-xhr'] = $url;
            $view->vars['attr']['href'] = '#';

            if (!empty($this->options['confirm'])) {
                $view->vars['attr']['data-confirm'] = $translator->trans($this->options['confirm']);
            }

            if (!empty($this->options['xhr_id'])) {
                $view->vars['attr']['data-xhr-id'] = $this->options['xhr_id'];
            }

            if (true === $this->options['spinner']) {
                $view->vars['attr']['spinner'] = 'true';
            }
        } else {
            $view->vars['attr']['href'] = $url;
        }

        $view->vars['attr']['class'] = $this->options['class'];

        $this->type->buildView($view, $this->options);

        if (!empty($this->options['extra_data'])) {
            $view->vars['attr']['data-extra-data'] = $this->options['extra_data'];
            $view->vars['attr']['class'] .= ' no-bind';
        }

        return $view;
    }
}
