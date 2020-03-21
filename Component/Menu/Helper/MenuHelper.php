<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 08/05/17
 * Time: 16:58.
 */

namespace Umbrella\CoreBundle\Component\Menu\Helper;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Translation\TranslatorInterface;
use Umbrella\CoreBundle\Component\Menu\Matcher\MenuMatcherInterface;
use Umbrella\CoreBundle\Component\Menu\Matcher\MenuRequestMatcher;
use Umbrella\CoreBundle\Component\Menu\MenuAuthorizationChecker;
use Umbrella\CoreBundle\Component\Menu\Model\Menu;
use Umbrella\CoreBundle\Component\Menu\Model\MenuNode;
use Umbrella\CoreBundle\Component\Menu\MenuProvider;
use Umbrella\CoreBundle\Component\Menu\MenuRendererProvider;
use Umbrella\CoreBundle\Component\Menu\Renderer\MenuRendererInterface;

/**
 * Class MenuHelper
 */
class MenuHelper
{
    /**
     * @var MenuProvider
     */
    private $provider;

    /**
     * @var MenuRendererProvider
     */
    private $rendererProvider;

    /**
     * @var MenuAuthorizationChecker
     */
    private $checker;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var MenuMatcherInterface
     */
    private $defaultMatcher;

    /**
     * MenuHelper constructor.
     *
     * @param MenuProvider $provider
     * @param MenuRendererProvider $rendererProvider
     * @param MenuAuthorizationChecker $checker
     * @param TranslatorInterface $translator
     * @param RequestStack $requestStack
     */
    public function __construct(
        MenuProvider $provider,
        MenuRendererProvider $rendererProvider,
        MenuAuthorizationChecker $checker,
        TranslatorInterface $translator,
        RequestStack $requestStack
    )
    {
        $this->provider = $provider;
        $this->rendererProvider = $rendererProvider;
        $this->checker = $checker;
        $this->translator = $translator;
        $this->defaultMatcher = new MenuRequestMatcher($requestStack);
    }

    /**
     * @param $name
     *
     * @return Menu
     */
    public function getMenu($name)
    {
        return $this->provider->get($name);
    }

    /**
     * @param $name
     * @return MenuRendererInterface
     */
    public function getRenderer($name)
    {
        return $this->rendererProvider->get($name);
    }

    /**
     * @param MenuNode $node
     *
     * @return bool
     */
    public function isGranted(MenuNode $node)
    {
        return $this->checker->isGranted($node);
    }

    /**
     * @param MenuNode $node
     * @param bool $checkAncestor
     * @return bool
     */
    public function isCurrent(MenuNode $node, $checkAncestor = true)
    {
        return $checkAncestor
            ? $this->defaultMatcher->isAncestor($node)
            : $this->defaultMatcher->isCurrent($node);
    }

    /**
     * @param Menu $menu
     * @return null|MenuNode
     */
    public function getCurrentNode(Menu $menu)
    {
        return $this->getCurrentNodeFromNode($menu->root);
    }

    /**
     * @param Menu $menu
     * @return array
     */
    public function buildBreadcrumb(Menu $menu)
    {
        $node = $this->getCurrentNodeFromNode($menu->root);

        $bc = array();

        while ($node !== null) {
            if ($node->type !== MenuNode::TYPE_ROOT) {
                $bc[] = array(
                    'label' => $node->translate
                        ? $this->translator->trans($menu->translationPrefix . $node->label)
                        : $node->label,
                    'url' => $node->url == MenuNode::DFT_URL ? null : $node->url,
                    'icon' => $node->icon,
                );
            }
            $node = $node->parent;
        }
        return array_reverse($bc);
    }

    /**
     * @param MenuNode $node
     * @return null|MenuNode
     */
    private function getCurrentNodeFromNode(MenuNode $node)
    {
        if ($this->defaultMatcher->isCurrent($node)) {
            return $node;
        }

        /** @var MenuNode $child */
        foreach ($node as $child) {
            $currentNode = $this->getCurrentNodeFromNode($child);
            if ($currentNode !== null) {
                return $currentNode;
            }
        }

        return null;
    }

}
