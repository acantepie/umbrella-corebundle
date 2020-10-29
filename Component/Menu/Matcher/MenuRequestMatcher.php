<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 04/06/17
 * Time: 21:48
 */

namespace Umbrella\CoreBundle\Component\Menu\Matcher;

use Symfony\Component\HttpFoundation\RequestStack;
use Umbrella\CoreBundle\Component\Menu\Model\MenuItem;

/**
 * Class MenuRequestMatcher
 */
class MenuRequestMatcher implements MenuMatcherInterface
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var \SplObjectStorage
     */
    private $cache;

    /**
     * MenuRequestMatcher constructor.
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
        $this->cache = new \SplObjectStorage();
    }

    /**
     * @inheritdoc
     */
    public function isCurrent(MenuItem $item)
    {
        if (null !== $item->isCurrent) {
            return $item->isCurrent;
        }

        if ($this->cache->contains($item)) {
            return $this->cache[$item];
        }

        $match = $this->isRequestMatching($item->route, $item->routeParams);
        $this->cache[$item] = $match;
        return $match;
    }

    /**
     * @inheritdoc
     */
    public function isAncestor(MenuItem $item)
    {
        if ($this->isCurrent($item)) {
            return true;
        }

        /** @var MenuItem $child */
        foreach ($item as $child) {
            if ($this->isAncestor($child)) {
                return true;
            }
        }
        return false;
    }

    /**
     *
     * @param $testRoute
     * @param  array $testRouteParams
     * @return bool
     */
    private function isRequestMatching($testRoute, array $testRouteParams = [])
    {
        $request = $this->requestStack->getMasterRequest();
        $route = $request->attributes->get('_route');
        if ($testRoute !== $route) {
            return false;
        }

        foreach ($testRouteParams as $key => $value) {
            if ($request->get($key) != $value) {
                return false;
            }
        }

        return true;
    }
}
