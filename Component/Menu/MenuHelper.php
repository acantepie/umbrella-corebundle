<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 08/05/17
 * Time: 16:58.
 */

namespace Umbrella\CoreBundle\Component\Menu;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;
use Umbrella\CoreBundle\Component\Menu\Matcher\MenuMatcherInterface;
use Umbrella\CoreBundle\Component\Menu\Matcher\MenuRequestMatcher;
use Umbrella\CoreBundle\Component\Menu\Model\Breadcrumb;
use Umbrella\CoreBundle\Component\Menu\Model\Menu;
use Umbrella\CoreBundle\Component\Menu\Model\MenuItem;

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
     * @param MenuProvider             $provider
     * @param MenuAuthorizationChecker $checker
     * @param TranslatorInterface      $translator
     * @param RequestStack             $requestStack
     */
    public function __construct(
        MenuProvider $provider,
        MenuAuthorizationChecker $checker,
        TranslatorInterface $translator,
        RequestStack $requestStack
    ) {
        $this->provider = $provider;
        $this->checker = $checker;
        $this->translator = $translator;
        $this->defaultMatcher = new MenuRequestMatcher($requestStack);
    }

    /**
     * @param $name
     *
     * @return Menu
     */
    public function getMenu($name = null)
    {
        return $this->provider->getMenu($name);
    }

    /**
     * @param $name
     *
     * @return Breadcrumb
     */
    public function getBreadcrumb($name = null)
    {
        return $this->provider->getBreadcrumb(
            $this->getCurrentItemFromItem($this->getMenu($name)->getRoot()),
            $name
        );
    }

    /**
     * @param null  $name
     * @param array $parameters
     *
     * @return string
     */
    public function renderMenu($name = null, array $parameters = [])
    {
        return $this->provider->renderMenu($this->getMenu($name), $name, $parameters);
    }

    /**
     * @param null  $name
     * @param array $parameters
     *
     * @return string
     */
    public function renderBreadcrumb($name = null, array $parameters = [])
    {
        return $this->provider->renderBreadcrumb($this->getBreadcrumb($name), $name, $parameters);
    }

    /**
     * @param MenuItem $item
     *
     * @return bool
     */
    public function isGranted(MenuItem $item)
    {
        return $this->checker->isGranted($item);
    }

    /**
     * @param MenuItem $item
     * @param bool     $checkAncestor
     *
     * @return bool
     */
    public function isCurrent(MenuItem $item, $checkAncestor = true)
    {
        return $checkAncestor
            ? $this->defaultMatcher->isAncestor($item)
            : $this->defaultMatcher->isCurrent($item);
    }

    /**
     * @param null $name
     *
     * @return MenuItem|null
     */
    public function getCurrentItem($name = null)
    {
        return $this->getCurrentItemFromItem($this->getMenu($name)->getRoot());
    }

    /**
     * @param MenuItem $item
     *
     * @return MenuItem|null
     */
    private function getCurrentItemFromItem(MenuItem $item)
    {
        if ($this->defaultMatcher->isCurrent($item)) {
            return $item;
        }

        /** @var MenuItem $child */
        foreach ($item as $child) {
            $currentItem = $this->getCurrentItemFromItem($child);
            if (null !== $currentItem) {
                return $currentItem;
            }
        }

        return null;
    }
}
