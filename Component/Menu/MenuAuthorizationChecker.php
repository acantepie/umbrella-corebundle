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
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\ExpressionLanguage;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Umbrella\CoreBundle\Component\Menu\Model\MenuNode;

/**
 * Class MenuAuthorizationChecker
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
     * @param TokenStorageInterface $tokenStorage
     * @param ExpressionLanguage $language
     * @param AuthenticationTrustResolverInterface $trustResolver
     * @param AuthorizationCheckerInterface $authChecker
     * @param RoleHierarchyInterface|null $roleHierarchy
     */
    public function __construct(TokenStorageInterface $tokenStorage, ExpressionLanguage $language,  AuthenticationTrustResolverInterface $trustResolver, AuthorizationCheckerInterface $authChecker, RoleHierarchyInterface $roleHierarchy = null)
    {
        $this->tokenStorage = $tokenStorage;
        $this->language = $language;
        $this->trustResolver = $trustResolver;
        $this->authChecker = $authChecker;
        $this->roleHierarchy = $roleHierarchy;
        $this->cache = new \SplObjectStorage();
    }

    /**
     * @param MenuNode $node
     * @return bool
     */
    public function isGranted(MenuNode $node)
    {

        if ($this->cache->contains($node)) {
            return $this->cache[$node];
        }

        // no user authenticated
        if (null === $this->tokenStorage->getToken()) {
            throw new \LogicException('To use the @MenuAuthorizationChecker service, your route needs to be behind a firewall.');
        }

        // no securityExpression => look at children
        if (empty($node->security)) {

            // no children => granted
            if (!$node->hasChildren()) {
                $this->cache[$node] = true;
                return true;
            }

            // one children is granted => granted
            foreach ($node as $child) {
                if ($this->isGranted($child)) {
                    $this->cache[$node] = true;
                    return true;
                }
            }


            // all children are forbidden => not granted
            $this->cache[$node] = false;
            return false;
        }

        $granted = $this->language->evaluate($node->security, $this->getVariables());
        $this->cache[$node] = $granted;
        return $granted;
    }

    /**
     * @return array
     */
    private function getVariables()
    {
        $token = $this->tokenStorage->getToken();

        if (null !== $this->roleHierarchy) {
            $roles = $this->roleHierarchy->getReachableRoles($token->getRoles());
        } else {
            $roles = $token->getRoles();
        }

        $variables = array(
            'token' => $token,
            'user' => $token->getUser(),
            'roles' => array_map(function ($role) { return $role->getRole(); }, $roles),
            'trust_resolver' => $this->trustResolver,
            // needed for the is_granted expression function
            'auth_checker' => $this->authChecker,
        );

        return $variables;
    }

}