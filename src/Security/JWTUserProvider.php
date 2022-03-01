<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class JWTUserProvider implements UserProviderInterface
{
    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }
    public function loadUserByIdentifier(string $identifier): UserInterface
    {

        $user = $this->userRepo->findOneBy(['username' => $identifier]);

        if (!$user) {
            throw new AccessDeniedException(sprintf('Unknow username: %s', $identifier));
        }
        
        return $user;
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException();
        }
        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return 'App\Entity\User' === $class || is_subclass_of($class, User::class);
    }
}