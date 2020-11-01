<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 19/04/20
 * Time: 16:59
 */

namespace Umbrella\CoreBundle\Component\Column\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Umbrella\CoreBundle\Utils\StringUtils;

/**
 * Class ToggleColumnType
 */
class ToggleColumnType extends PropertyColumnType
{
    const TEMPLATE = '<div class="js-toggle-widget">'
    . '<input type="checkbox" id="%s" %s data-switch="%s" data-yes-url="%s" data-no-url="%s">'
    . '<label for="%s" data-on-label="%s" data-off-label="%s" class="mb-0 d-block"></label>'
    . '</div>';

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * ToggleColumnType constructor.
     *
     * @param TranslatorInterface $translator
     * @param RouterInterface     $router
     */
    public function __construct(TranslatorInterface $translator, RouterInterface $router)
    {
        $this->translator = $translator;
        $this->router = $router;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function render($entity, array $options)
    {
        $value = $this->accessor->getValue($entity, $options['property_path']);
        $cssId = StringUtils::random(8);

        $routeParams = is_array($options['route_params'])
            ? $options['route_params']
            : call_user_func($options['route_params'], $entity, $options);

        $yesUrl = $this->router->generate($options['route'], array_merge($routeParams, ['value' => 1]));
        $noUrl = $this->router->generate($options['route'], array_merge($routeParams, ['value' => 0]));

        return sprintf(
            self::TEMPLATE,
            $cssId,
            $value === $options['yes_value'] ? 'checked="checked"' : '',
            $options['switch_type'],
            $yesUrl,
            $noUrl,
            $cssId,
            $this->translator->trans($options['yes_label']),
            $this->translator->trans($options['no_label']));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('yes_value', true)

            ->setDefault('yes_label', 'common.yes')
            ->setAllowedTypes('yes_label', 'string')

            ->setDefault('no_label', 'common.no')
            ->setAllowedTypes('no_label', 'string')

            ->setDefault('switch_type', 'success')
            ->setAllowedTypes('switch_type', 'string')

            ->setRequired('route')
            ->setAllowedTypes('route', 'string')

            ->setDefault('route_params', [])
            ->setAllowedTypes('route_params', ['array', 'callable']);
    }
}
