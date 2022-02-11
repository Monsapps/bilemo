<?php

namespace App\Service;

use App\Entity\User;
use App\Model\User as ModelUser;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService extends BaseService
{
    private $managerRegistry;
    private $router;
    private $userRepo;

    public function __construct(
        ManagerRegistry $managerRegistry,
        UrlGeneratorInterface $router,
        UserRepository $userRepo,
        ValidatorInterface $validator)
    {
        $this->managerRegistry = $managerRegistry;
        $this->router = $router;
        $this->userRepo = $userRepo;

        parent::__construct($validator);
    }

    public function getUserList(ParamFetcherInterface $paramFetch, User $client = null)
    {

        $pagerFanta = $this->userRepo->search(
            $paramFetch->get('keyword'),
            $paramFetch->get('order'),
            $paramFetch->get('limit'),
            $paramFetch->get('page'),
            $client
        );

        return new ModelUser($pagerFanta, $this->router);
    }

    public function addUser(array $data, User $client = null, array $roles = null): User
    {
        $user = new User();

        // Add roles & user when authentication
        //$roles = ['USER_ROLE'];

        $this->addUserInfos($user, $data, $client, $roles);

        $this->entityValidator($user);

        $entityManager = $this->managerRegistry->getManager();

        $entityManager->persist($user);

        $entityManager->flush();

        return $user;
    }

    public function editUser(User $user, array $data)
    {

        $this->addUserInfos($user, $data);

        $this->entityValidator($user);

        $entityManager = $this->managerRegistry->getManager();

        $entityManager->flush();

    }

    public function deleteUser(User $user): void
    {
        $entityManager = $this->managerRegistry->getManager();
        $entityManager->remove($user);
        $entityManager->flush();
    }

    private function addUserInfos(User $user, array $data, User $client = null, array $roles = null): User
    {

        (!empty($data['username'])) ? $user->setUsername($data['username']) : '';

        (!empty($data['email'])) ? $user->setEmail($data['email']) : '';

        if($client !== null) {

            $user->setClient($client);

            $client->setUser($user);
        }

        ($roles !== null) ? $user->setRoles($roles) : '';

        return $user;
    }
}