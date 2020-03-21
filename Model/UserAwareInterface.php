<?php


namespace Umbrella\CoreBundle\Model;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Umbrella\UserBundle\Entity\User;

/**
 * Interface UserAwareInterface
 * @package Umbrella\CoreBundle\Model
 */
interface UserAwareInterface
{
    /**
     * @return UserInterface
     */
    public function getUser();

    /**
     * @param TokenStorageInterface $tokenStorage
     */
    public function setTokenStorage(TokenStorageInterface $tokenStorage);

}