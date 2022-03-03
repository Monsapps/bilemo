<?php

namespace App\Test\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserServiceTest extends KernelTestCase
{
    private $managerRegistry;
    private $hasher;
    private $userRepo;
    private $router;
    private $validator;

    protected function setUp(): void
    {
        $container = static::getContainer();
        $this->managerRegistry = $container->get(ManagerRegistry::class);
        $this->hasher = $container->get(UserPasswordHasherInterface::class);
        $this->userRepo = $container->get(UserRepository::class);
        $this->router = $container->get(UrlGeneratorInterface::class);
        $this->validator = $container->get(ValidatorInterface::class);
    }

    public function testAddUserAsClient(): void
    {

        $userService = new UserService($this->managerRegistry, $this->router, $this->hasher, $this->userRepo, $this->validator);

        $data = [
            "username" => "TestUser",
            "password" => "123456test",
            "email" => "user-client@mail.com"
        ];

        $user = $userService->addUser($data, $this->getClient());

        $this->assertSame($user->getUsername(), $this->getUser()->getUsername());
        $this->assertSame($user->getEmail(), $this->getUser()->getEmail());
        $this->assertContains('ROLE_USER', $user->getRoles());
        $this->assertNotContains('ROLE_CLIENT', $user->getRoles());
        $this->assertNotContains('ROLE_BILEMO', $user->getRoles());

    }

    public function testAddUserClientAsAdmin(): void
    {

        $userService = new UserService($this->managerRegistry, $this->router, $this->hasher, $this->userRepo, $this->validator);

        $data = [
            "username" => "TestUser",
            "password" => "123456test",
            "email" => "user-client@mail.com"
        ];

        $user = $userService->addUser($data, $this->getClient());

        $this->assertSame($user->getUsername(), $this->getUser()->getUsername());
        $this->assertSame($user->getEmail(), $this->getUser()->getEmail());
        $this->assertContains('ROLE_USER', $user->getRoles());
        $this->assertNotContains('ROLE_CLIENT', $user->getRoles());
        $this->assertNotContains('ROLE_BILEMO', $user->getRoles());

    }

    public function testAddClientAsAdmin(): void
    {

        $userService = new UserService($this->managerRegistry, $this->router, $this->hasher, $this->userRepo, $this->validator);

        $data = [
            "username" => "TestClient",
            "password" => "123456test",
            "email" => "client-admin@mail.com"
        ];

        $client = $userService->addUser($data, $this->getAdmin(), ['ROLE_CLIENT']);

        $this->assertSame($client->getUsername(), $this->getClient()->getUsername());
        $this->assertSame($client->getEmail(), $this->getClient()->getEmail());
        $this->assertContains('ROLE_USER', $client->getRoles());
        $this->assertContains('ROLE_CLIENT', $client->getRoles());
        $this->assertNotContains('ROLE_BILEMO', $client->getRoles());
    }

    public function testOnlyParentClientCanEditUser(): void
    {
        $this->expectException(\Exception::class);

        $userService = new UserService($this->managerRegistry, $this->router, $this->hasher, $this->userRepo, $this->validator);

        $data = [
            "username" => "Dummy",
            "password" => "123456test",
            "email" => "abde@mail.com"
        ];

        $userService->editUser($this->getUser(), $data, $this->getAdmin());
    }

    public function testOnlyParentClientCanDeleteUser(): void
    {
        $this->expectException(\Exception::class);

        $userService = new UserService($this->managerRegistry, $this->router, $this->hasher, $this->userRepo, $this->validator);

        $userService->deleteUser($this->getUser(), $this->getAdmin());
    }

    private function getAdmin() : User
    {
        $admin = new User();
        $admin->setUsername('TestAdmin');
        $admin->setEmail('test-admin@mail.com');
        $admin->setPassword('123456test');
        $admin->setRoles(['ROLE_BILEMO']);

        return $admin;
    }

    private function getClient() : User
    {
        $client = new User();
        $client->setUsername('TestClient');
        $client->setEmail('client-admin@mail.com');
        $client->setPassword('123456test');
        $client->setRoles(['ROLE_CLIENT']);

        return $client;
    }

    private function getUser() : User
    {
        $user = new User();
        $user->setUsername('TestUser');
        $user->setEmail('user-client@mail.com');
        $user->setPassword('123456test');
        $user->setRoles(['ROLE_USER']);
        $user->setClient($this->getClient());
        $this->getClient()->setUser($user);

        return $user;
    }
}