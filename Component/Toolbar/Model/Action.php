<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 27/06/17
 * Time: 22:07
 */
namespace Umbrella\CoreBundle\Component\Toolbar\Model;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Umbrella\CoreBundle\Model\OptionsAwareInterface;
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * Class Action
 */
class Action implements OptionsAwareInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    // options

    /**
     * @var array
     */
    public $options;

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $template;

    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $url;

    /**
     * @var boolean
     */
    public $xhr;

    /**
     * @var string
     */
    public $class;

    /**
     * @var string
     */
    public $confirm;

    /**
     * @var string
     */
    public $iconClass;

    /**
     * @var string
     */
    public $translationPrefix = 'action.';

    /**
     * @var null|array
     */
    public $attributes;

    /**
     * Action constructor.
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options = array())
    {
        $this->options = $options;

        $this->id = $options['id'];

        $this->url = ArrayUtils::get($options, 'url');

        $route = ArrayUtils::get($options, 'route');
        if (empty($this->url) && !empty($route)) {
            $this->url = $this->router->generate($route, $options['route_params']);
        }

        $this->xhr = ArrayUtils::get($options, 'xhr');
        $this->template = ArrayUtils::get($options, 'template');
        $this->class = ArrayUtils::get($options, 'class');
        $this->confirm = ArrayUtils::get($options, 'confirm');
        $this->iconClass = ArrayUtils::get($options, 'icon_class');
        $this->label = ArrayUtils::get($options, 'label', $this->id);
        $this->attributes = ArrayUtils::get($options, 'attr');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array(
            'id',
        ));

        $resolver->setDefined(array(
            'label',
            'template',
            'route',
            'route_params',
            'url',
            'xhr',
            'confirm',
            'class',
            'icon_class',
            'attr',
        ));

        $resolver->setAllowedTypes('xhr', 'boolean');
        $resolver->setAllowedTypes('attr', 'array');
        $resolver->setAllowedTypes('route_params', 'array');

        $resolver->setDefault('route_params', array());
        $resolver->setDefault('xhr', true);
        $resolver->setDefault('template', '@UmbrellaCore/Toolbar/Action/action.html.twig');
        $resolver->setDefault('attr', array());
    }
}