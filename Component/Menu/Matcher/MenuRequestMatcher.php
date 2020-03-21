<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 04/06/17
 * Time: 21:48
 */

namespace Umbrella\CoreBundle\Component\Menu\Matcher;

use Symfony\Component\HttpFoundation\RequestStack;
use Umbrella\CoreBundle\Component\Menu\Model\MenuNode;

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
    public function isCurrent(MenuNode $node)
    {
        if (null !== $node->isCurrent) {
            return $node->isCurrent;
        }

        if ($this->cache->contains($node)) {
            return $this->cache[$node];
        }

        $match = $this->isRequestMatching($node->route, $node->routeParams);
        $this->cache[$node] = $match;
        return $match;
    }

    /**
     * @inheritdoc
     */
    public function isAncestor(MenuNode $node)
    {
        if ($this->isCurrent($node)) {
            return true;
        }

        /** @var MenuNode $child */
        foreach($node as $child) {
            if ($this->isAncestor($child)) {
                return true;
            }
        }
        return false;
    }

    /**
     *
     * @param $testRoute
     * @param array $testRouteParams
     * @return bool
     */
    private function isRequestMatching($testRoute, array $testRouteParams = array())
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
