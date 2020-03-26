<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 27/06/17
 * Time: 22:07
 */
namespace Umbrella\CoreBundle\Component\Toolbar\Model;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Model\OptionsAwareInterface;

/**
 * Class Action
 */
class Action implements OptionsAwareInterface
{
    /**
     * @var array
     */
    private $options;


    /**
     * @inheritdoc
     */
    public function setOptions(array $options = array())
    {
        $this->options = $options;
    }

    /**
     * @inheritdoc
     */
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

            ->setDefault('template', '@UmbrellaCore/Toolbar/Action/action.html.twig')
            ->setAllowedTypes('template', 'string')

            ->setDefault('xhr', true)
            ->setAllowedTypes('xhr', 'bool')

            ->setDefault('route', null)
            ->setAllowedTypes('route', ['null', 'string'])

            ->setDefault('route_params', [])
            ->setAllowedTypes('route_params', 'array')

            ->setDefault('url', null)
            ->setAllowedTypes('url', ['string', 'null'])

            ->setDefault('confirm', null)
            ->setAllowedTypes('confirm', ['null', 'string'])

            ->setDefault('class', null)
            ->setAllowedTypes('class', ['null', 'string'])

            ->setDefault('icon', null)
            ->setAllowedTypes('class', ['null', 'string']);
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->options['template'];
    }

    /**
     * @return array
     */
    public function getViewOptions()
    {
        return $this->options;
    }
}