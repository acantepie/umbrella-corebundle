<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 16:11.
 */

namespace Umbrella\CoreBundle\Component\Menu;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Umbrella\CoreBundle\Component\Menu\Model\Menu;
use Umbrella\CoreBundle\Component\Menu\Model\MenuItem;

/**
 * Class MenuBuilder.
 */
class MenuFactory
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * MenuFactory constructor.
     *
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @return Menu
     */
    public function createMenu()
    {
        return new Menu($this);
    }

    /**
     * @param $id
     * @param array $options
     *
     * @return MenuItem
     */
    public function _createItem($id, array $options = [])
    {
        $resolver = new OptionsResolver();
        $resolver
            ->setDefault('label', function (Options $options) use ($id) {
                return sprintf('menu.%s', $id);
            })
            ->setAllowedTypes('label', 'string')

            ->setDefault('translation_domain', 'messages')
            ->setAllowedTypes('translation_domain', ['null', 'string'])

            ->setDefault('icon', null)
            ->setAllowedTypes('icon', ['null', 'string'])

            ->setDefault('security', null)
            ->setAllowedTypes('security', ['null', 'string'])

            ->setDefault('route', null)
            ->setAllowedTypes('route', ['null', 'string'])

            ->setDefault('route_params', [])
            ->setAllowedTypes('route_params', ['array'])

            ->setDefault('children', [])
            ->setAllowedTypes('children', []);

        $resolvedOptions = $resolver->resolve($options);

        $i = new MenuItem($id, $this);
        $i->translationDomain = $resolvedOptions['translation_domain'];
        $i->label = $resolvedOptions['label'];
        $i->icon = $resolvedOptions['icon'];
        $i->security = $resolvedOptions['security'];
        $i->route = $resolvedOptions['route'];
        $i->routeParams = $resolvedOptions['route_params'];

        foreach ($resolvedOptions['children'] as $id => $childOptions) {
            $i->addChild($id, $childOptions);
        }

        return $i;
    }
}
