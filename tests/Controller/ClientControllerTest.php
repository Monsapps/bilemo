<?php

namespace App\Test\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ClientControllerTest extends WebTestCase
{

    private const HTTP_HOST = "bilemo.local";

    public function testUserCantGetClientList(): void
    {
        $client = $this->createAuthenticatedClient('username0', 'pass_1234');

        $client->request('GET', '/clients');

        $this->assertResponseStatusCodeSame(403);
    }

    public function testClientCantGetClientList(): void
    {
        $client = $this->createAuthenticatedClient('client', 'pass_1234');

        $client->request('GET', '/clients');

        $this->assertResponseStatusCodeSame(403);
    }

    public function testAdminCanGetClientList(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/clients');

        $this->assertResponseStatusCodeSame(200);
    }

    public function testClientDetailsIsUp(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/clients/2');

        $this->assertResponseStatusCodeSame(200);
    }

    public function testClientDetailsWithAdminIdIsDenied(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/clients/1');

        $this->assertResponseStatusCodeSame(403);
    }

    public function testClientPostGoodData(): void
    {
        $client = $this->createAuthenticatedClient();

        $data = '{
            "username": "New client",
            "email": "email@gmail.com",
            "password": "pass_1234"
        }';

        $client->request(
            'POST',
            '/clients',
            [],
            [],
            [],
            $data);

        $this->assertResponseStatusCodeSame(201);
    }

    public function testClientPatchData(): void
    {
        $client = $this->createAuthenticatedClient();

        $data = '{
            "username": "Client one edited"
        }';

        $client->request(
            'PATCH',
            '/clients/2',
            [],
            [],
            [],
            $data);

        $this->assertResponseStatusCodeSame(200);
    }

    public function testClientDelete(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('DELETE', '/clients/2');

        $this->assertResponseStatusCodeSame(204);
    }

    /**
     * Create a client with a default Authorization header.
     *
     * @param string $username
     * @param string $password
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function createAuthenticatedClient($username = 'admin', $password = 'pass_1234')
    {
        $client = static::createClient();

        $client->setServerParameter("HTTP_HOST", self::HTTP_HOST);

        $data = '{
            "username": "'. $username .'",
            "password": "'. $password .'"
        }';

        $client->request(
            'POST',
            '/login',
            [],
            [],
            [],
            $data);

        $data = json_decode($client->getResponse()->getContent(), true);

        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        return $client;
    }
}
