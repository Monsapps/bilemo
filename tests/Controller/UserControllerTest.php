<?php

namespace App\Test\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private const HTTP_HOST = "bilemo.local";

    public function testUserListIsUp(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/users');

        $this->assertResponseStatusCodeSame(200);

    }

    public function testUserListJsonResponse(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/users');

        $response = $client->getResponse();

        $this->assertTrue($response->headers->contains("Content-Type", "application/json"));
    }

    public function testUserListPagination(): void
    {

        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/users');

        $response = $client->getResponse();

        $this->assertArrayHasKey("_links", json_decode($response->getContent(), true));
    }

    public function testUserListSearch(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/users?keyword=client');

        $response = $client->getResponse();

        $arrayResponse = json_decode($response->getContent(), true);

        $this->assertEquals(1, count($arrayResponse['data']));
    }

    public function testUserDetailsPageIsUp(): void
    {

        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/users/1');

        $this->assertResponseStatusCodeSame(200);
    }

    public function testUserPostGoodData(): void
    {
        $client = $this->createAuthenticatedClient();

        $data = '{
            "username": "New user",
            "email": "email@gmail.com",
            "password": "password12345"
        }';

        $client->request(
            'POST',
            '/users',
            [],
            [],
            [],
            $data);

        $this->assertResponseStatusCodeSame(201);
    }

    public function testUserPostBadData(): void
    {
        $client = $this->createAuthenticatedClient();

        $data = '{}';

        $client->request(
            'POST',
            '/users',
            [],
            [],
            [],
            $data);

        $this->assertResponseStatusCodeSame(400);
    }

    public function testUserPatchData(): void
    {
        $client = $this->createAuthenticatedClient();

        $data = '{
            "username": "Product edited"
        }';

        $client->request(
            'PATCH',
            '/users/15',
            [],
            [],
            [],
            $data);

        $this->assertResponseStatusCodeSame(200);
    }

    public function testClientCantPatchDataOnOtherClient(): void
    {
        $client = $this->createAuthenticatedClient('client', 'pass_1234');

        $data = '{
            "username": "Product edited"
        }';

        $client->request(
            'PATCH',
            '/users/1',
            [],
            [],
            [],
            $data);

        $this->assertResponseStatusCodeSame(401);
    }

    public function testUserDelete(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('DELETE', '/users/15');

        $this->assertResponseStatusCodeSame(204);
    }

    public function testClientCantDeleteOtherUserClient(): void
    {
        $client = $this->createAuthenticatedClient('client', 'pass_1234');

        $client->request('DELETE', '/users/1');

        $this->assertResponseStatusCodeSame(401);
    }

    public function testUserRoleCantGetUserList(): void
    {
        $client = $this->createAuthenticatedClient('username0', 'pass_1234');

        $client->request('GET', '/users');

        $this->assertResponseStatusCodeSame(401);
    }

    public function testUserRoleCantDeleteUser(): void
    {
        $client = $this->createAuthenticatedClient('username0', 'pass_1234');

        $client->request('DELETE', '/users/15');

        $this->assertResponseStatusCodeSame(401);
    }

    public function testUserListPreviousPage(): void
    {

        $client = $this->createAuthenticatedClient('client', 'pass_1234');

        $client->request('GET', '/users?page=2&limit=5');

        $response = $client->getResponse();

        $arrayResponse = json_decode($response->getContent(), true);

        $this->assertArrayHasKey("previous_page", $arrayResponse['_links']);

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