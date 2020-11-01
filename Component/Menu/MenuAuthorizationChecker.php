<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 04/06/17
 * Time: 21:52
 */

namespace Umbrella\CoreBundle\Component\Menu;

use Sensio\Bundle\FrameworkExtraBundle\EventListener\SecurityListener;
use Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolverInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\ExpressionLanguage;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Umbrella\CoreBundle\Component\Menu\Model\MenuItem;

/**
 * Class MenuAuthorizationChecker
 *
 * @see SecurityListener
 */
class MenuAuthorizationChecker
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var
     */
    private $language;

    /**
     * @var AuthenticationTrustResolverInterface
     */
    private $trustResolver;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authChecker;

    /**
     * @var RoleHierarchyInterface
     */
    private $roleHierarchy;

    /**
     * @var \SplObjectStorage
     */
    private $cache;

    /**
     * MenuAuthorizationChecker constructor.
     *
     * @param TokenStorageInterface                $tokenStorage
     * @param ExpressionLanguage                   $language
     * @param AuthenticationTrustResolverInterface $trustResolver
     * @param AuthorizationCheckerInterface        $authChecker
     * @param RoleHierarchyInterface|null          $roleHierarchy
     */
    public function __construct(TokenStorageInterface $tokenStorage, ExpressionLanguage $language, AuthenticationTrustResolverInterface $trustResolver, AuthorizationCheckerInterface $authChecker, RoleHierarchyInterface $roleHierarchy = null)
    {
        $this->tokenStorage = $tokenStorage;
        $this->language = $language;
        $this->trustResolver = $trustResolver;
        $this->authChecker = $authChecker;
        $this->roleHierarchy = $roleHierarchy;
        $this->cache = new \SplObjectStorage();
    }

    /**
     * @param MenuItem $item
     *
     * @return bool
     */
    public function isGranted(MenuItem $item)
    {
        if ($this->cache->contains($item)) {
            return $this->cache[$item];
        }

        // no user authenticated
        if (null === $this->tokenStorage->getToken()) {
            throw new \LogicException('To use the @MenuAuthorizationChecker service, your route needs to be behind a firewall.');
        }

        // no securityExpression => look at children
        if (empty($item->security)) {
            // no children => granted
            if (!$item->hasChildren()) {
                $this->cache[$item] = true;

                return true;
            }

            // one children is granted => granted
            foreach ($item as $child) {
                if ($this->isGranted($child)) {
                    $this->cache[$item] = true;

                    return true;
                }
            }

            // all children are forbidden => not granted
            $this->cache[$item] = false;

            return false;
        }

        $granted = $this->language->evaluate($item->security, $this->getVariables());
        $this->cache[$item] = $granted;

        return $granted;
    }

    /**
     * @return array
     */
    private function getVariables()
    {
        $token = $this->tokenStorage->getToken();

        $variables = [
            'token' => $token,
            'user' => $token->getUser(),
            'roles' => $this->getRoles($token),
            'trust_resolver' => $this->trustResolver,
            // needed for the is_granted expression function
            'auth_checker' => $this->authChecker,
        ];

        return $variables;
    }

    /**
     * @param TokenInterface $token
     *
     * @return array
     */
    private function getRoles(TokenInterface $token): array
    {
        if (method_exists($this->roleHierarchy, 'getReachableRoleNames')) {
            if (null !== $this->roleHierarchy) {
                $roles = $this->roleHierarchy->getReachableRoleNames($token->getRoleNames());
            } else {
                $roles = $token->getRoleNames();
            }
        } else {
            if (null !== $this->roleHierarchy) {
                $roles = $this->roleHierarchy->getReachableRoles($token->getRoles());
            } else {
                $roles = $token->getRoles();
            }

            $roles = array_map(function ($role) {
                return $role->getRole();
            }, $roles);
        }

        return $roles;
    }
}
