<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 05/04/18
 * Time: 15:33
 */

namespace Umbrella\CoreBundle\Component\Column\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Umbrella\CoreBundle\Utils\HtmlUtils;

/**
 * Class EnableColumnType
 */
class BooleanColumnType extends PropertyColumnType
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * EnableColumnType constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        parent::__construct();
        $this->translator = $translator;
    }

    /**
     * @param $entity
     * @param array $options
     * @return string
     */
    public function render($entity, array $options)
    {
        switch ($this->accessor->getValue($entity, $options['property_path'])) {
            case true:
                return sprintf(
                    '<span class="badge badge-success">%s %s</span>',
                    HtmlUtils::render_icon($options['yes_icon']),
                    $this->translator->trans($options['yes_value'])
                );

            case false:
                return sprintf(
                    '<span class="badge badge-danger">%s %s</span>',
                    HtmlUtils::render_icon($options['no_icon']),
                    $this->translator->trans($options['no_value'])
                );
            default:
                return '';
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('yes_value', 'common.yes')
            ->setAllowedTypes('yes_value', 'string')

            ->setDefault('no_value', 'common.no')
            ->setAllowedTypes('no_value', 'string')

            ->setDefault('yes_icon', 'mdi mdi-check mr-1')
            ->setAllowedTypes('yes_icon', 'string')

            ->setDefault('no_icon', 'mdi mdi-cancel mr-1')
            ->setAllowedTypes('no_icon', 'string');
    }


}