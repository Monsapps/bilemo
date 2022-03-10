<?php

namespace App\Service;

use App\Entity\User;
use App\Model\User as ModelUser;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class UserService extends BaseService
{
    private $hasher;
    private $managerRegistry;
    private $router;
    private $userRepo;

    public function __construct(
        ManagerRegistry $managerRegistry,
        UrlGeneratorInterface $router,
        UserPasswordHasherInterface $hasher,
        UserRepository $userRepo,
        ValidatorInterface $validator)
    {
        $this->hasher = $hasher;
        $this->managerRegistry = $managerRegistry;
        $this->router = $router;
        $this->userRepo = $userRepo;

        parent::__construct($validator);
    }

    public function getUserList(ParamFetcherInterface $paramFetch, User $parent = null)
    {

        if(in_array('ROLE_BILEMO', $parent->getRoles())) {
            $pagerFanta = $this->userRepo->search(
                $paramFetch->get('keyword'),
                $paramFetch->get('order'),
                $paramFetch->get('limit'),
                $paramFetch->get('page'),
                null,
                'ROLE_CLIENT'
            );
            return new ModelUser($pagerFanta, $this->router, 'client');
        }

        $pagerFanta = $this->userRepo->search(
            $paramFetch->get('keyword'),
            $paramFetch->get('order'),
            $paramFetch->get('limit'),
            $paramFetch->get('page'),
            $parent
        );

        return new ModelUser($pagerFanta, $this->router, 'user');
    }

    public function getUserDetails(User $user, User $client = null): User
    {
        $user = $this->userRepo->findOneBy([
            'id' => $user->getId(),
            'client' => $client
        ]);

        return $user;
    }

    public function addUser(array $data, User $parent = null, array $roles = null): User
    {
        $user = new User();

        $role = array();

        // If parent is admin he can create own user and client
        if(in_array('ROLE_BILEMO', $parent->getRoles())) {
            $role[] = 'ROLE_USER';
            if(null !== $roles) {
                //client have no parent
                $parent = null;
                $role = $roles;
            }
        }

        $this->addUserInfos($user, $data, $parent, $role);

        $this->entityValidator($user);

        $entityManager = $this->managerRegistry->getManager();

        $entityManager->persist($user);

        $entityManager->flush();

        return $user;
    }

    public function editUser(User $user, array $data, User $parent = null): User
    {

        if(isset($parent) && $user->getClient() !== $parent) {
            throw new \Exception('User and client not match', Response::HTTP_BAD_REQUEST);
        }

        $this->addUserInfos($user, $data);

        $this->entityValidator($user);

        $entityManager = $this->managerRegistry->getManager();

        $entityManager->flush();

        return $user;
    }

    public function deleteUser(User $user, User $parent = null)
    {

        if(isset($parent) && $user->getClient() !== $parent) {
            throw new \Exception('User and client not match', Response::HTTP_BAD_REQUEST);
        }

        $entityManager = $this->managerRegistry->getManager();
        $entityManager->remove($user);
        $entityManager->flush();
    }

    private function addUserInfos(User $user, array $data, User $client = null, array $roles = null): User
    {

        (!empty($data['username'])) ? $user->setUsername($data['username']) : '';

        (!empty($data['email'])) ? $user->setEmail($data['email']) : '';

        (!empty($data['password'])) ? $user->setPassword($this->hasher->hashPassword($user, $data['password'])) : '';

        if($client !== null) {

            $user->setClient($client);

            $client->setUser($user);
        }

        ($roles !== null) ? $user->setRoles($roles) : '';

        return $user;
    }
}