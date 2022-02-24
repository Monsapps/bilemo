<?php

namespace App\Test\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testSetterGetterEmail(): void
    {
        $user = new User();

        $user->setEmail('example@mail.com');

        $this->assertEquals('example@mail.com', $user->getEmail());
    }

    public function testGetIdentifer(): void
    {
        $user = new User();

        $user->setUsername('username_test');

        $this->assertEquals('username_test', $user->getUserIdentifier());
    }

    public function testSetterGetterUser(): void
    {
        $user = new User();
        $user->setUsername('user1');

        $client = new User();
        $client->setUsername('client1');
        $client->setUser($user);

        $this->assertContains($user, $client->getUsers());
    }
}