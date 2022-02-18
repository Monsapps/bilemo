<?php

namespace App\Security;

use App\Entity\Product;
use App\Entity\User;
use App\Model\Product as ModelProduct;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProductVoter extends Voter
{
    const GET = 'get';
    const POST = 'post';
    const PATCH = 'patch';
    const DELETE = 'delete';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        if (!in_array($attribute, [self::GET, self::POST, self::PATCH, self::DELETE])) {
            return false;
        }

        if(!$subject instanceof Product && !$subject instanceof ModelProduct) {
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

        if ($this->security->isGranted('ROLE_BILEMO')) {
            return true;
        }

        switch($attribute) {
            case self::GET: 
                if ($this->security->isGranted('ROLE_USER')) {
                    return true;
                }
                if ($this->security->isGranted('ROLE_CLIENT')) {
                    return true;
                }
            case self::PATCH:
            case self::POST:
            case self::DELETE:
                return false;
        }
        throw new \LogicException('This code should not be reached!');
    }
}