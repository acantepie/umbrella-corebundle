<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 27/06/17
 * Time: 22:07
 */
namespace Umbrella\CoreBundle\Component\Toolbar\Action;

use Symfony\Component\OptionsResolver\Options;
use Umbrella\CoreBundle\Model\OptionsAwareInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Action
 */
class Action implements OptionsAwareInterface
{
    const DATA_DATATABLE_FILTER = 'dt_filter';
    const DATA_DATATABLE_SELECTION = 'dt_selection';

    /**
     * @var array
     */
    private $options;

    /**
     * @inheritdoc
     */
    public function setOptions(array $options = [])
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

            ->setDefault('template', '@UmbrellaCore/Toolbar/action.html.twig')
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
