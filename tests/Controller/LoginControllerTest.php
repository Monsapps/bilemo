<?php

namespace App\Test\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{

    private const HTTP_HOST = "bilemo.local";

    public function testLoginController(): void
    {
        $client = static::createClient();
        $client->setServerParameter("HTTP_HOST", self::HTTP_HOST);

        $data = '{
            "username": "admin",
            "password": "pass_1234"
        }';

        $client->request(
            'POST',
            '/login',
            [],
            [],
            [],
            $data);

        $this->assertResponseStatusCodeSame(200);
    }
}