<?php

namespace App\Security;

use App\Entity\User;
use App\Model\User as ModelUser;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class UserVoter extends Voter
{
    const GET_USER = 'get';
    const POST_USER = 'post';
    const PATCH_USER = 'patch';
    const DELETE_USER = 'delete';
    const GET_CLIENT = 'get_client';
    const GET_CLIENT_DETAIL = 'get_client_details';
    const POST_CLIENT = 'post_client';
    const PATCH_CLIENT = 'patch_client';
    const DELETE_CLIENT = 'delete_client';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        if (!in_array($attribute,
            [
                self::GET_USER,
                self::POST_USER,
                self::PATCH_USER,
                self::DELETE_USER,
                self::GET_CLIENT,
                self::GET_CLIENT_DETAIL,
                self::POST_CLIENT,
                self::PATCH_CLIENT,
                self::DELETE_CLIENT
            ])) {
            return false;
        }

        if(!$subject instanceof User && !$subject instanceof ModelUser) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        switch($attribute) {
            case self::GET_USER:
            case self::POST_USER:
                if ($this->security->isGranted('ROLE_BILEMO') || $this->security->isGranted('ROLE_CLIENT')) {
                    return true;
                }
                if ($this->security->isGranted('ROLE_USER')) {
                    return false;
                }
            case self::PATCH_USER:
            case self::DELETE_USER:
                if ($this->security->isGranted('ROLE_BILEMO')) {
                    return true;
                }
                if ($this->security->isGranted('ROLE_CLIENT')) {
                    return $this->isParent($subject, $user);
                }
                if ($this->security->isGranted('ROLE_USER')) {
                    return false;
                }
            case self::GET_CLIENT:
                if ($this->security->isGranted('ROLE_BILEMO')) {
                    return true;
                }
                return false;
            case self::GET_CLIENT_DETAIL:
                if ($this->security->isGranted('ROLE_BILEMO')) {
                    return $this->isClient($subject);
                }
                return false;
            case self::POST_CLIENT:
                if ($this->security->isGranted('ROLE_BILEMO')) {
                    return true;
                }
                return false;
            case self::PATCH_CLIENT:
                if ($this->security->isGranted('ROLE_BILEMO')) {
                    return $this->isClient($subject);
                }
                return false;
            case self::DELETE_CLIENT:
                if ($this->security->isGranted('ROLE_BILEMO')) {
                    return $this->isClient($subject);
                }
                return false;
        }
        throw new \LogicException('This code should not be reached!');
    }

    private function isParent(User $user, User $client)
    {
        if(($user->getClient() === $client)) {
            return true;
        }
        return false;
    }

    private function isClient(User $user)
    {
        foreach($user->getRoles() as $role) {
            $roles[] = $role;
        }

        if(in_array('ROLE_CLIENT', $roles)) {
            return true;
        }
        return false;
    }
}
