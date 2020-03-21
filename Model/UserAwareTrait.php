<?php


namespace Umbrella\CoreBundle\Model;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Trait UserAwareTrait
 * @package Umbrella\CoreBundle\Model
 */
trait UserAwareTrait
{
    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @param TokenStorageInterface $tokenStorage
     */
    public function setTokenStorage(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param null $expectedClass
     * @return object|null
     */
    public function getUser($expectedClass = null)
    {
        if (!$this->tokenStorage->getToken()) {
            return null;
        }

        if (!$this->tokenStorage->getToken()->getUser()) {
            return null;
        }

        $user = $this->tokenStorage->getToken()->getUser();

        return $expectedClass === null
            ? $user
            : $user instanceof $expectedClass ? $user : null;
    }


}