<?php

namespace App\Test\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private const HTTP_HOST = "bilemo.local";

    public function testUserListIsUp(): void
    {
        $client = static::createClient();
        $client->setServerParameter("HTTP_HOST", self::HTTP_HOST);

        $client->request('GET', '/users');

        $this->assertResponseStatusCodeSame(200);

    }

    public function testUserListJsonResponse(): void
    {
        $client = static::createClient();
        $client->setServerParameter("HTTP_HOST", self::HTTP_HOST);

        $client->request('GET', '/users');

        $response = $client->getResponse();

        $this->assertTrue($response->headers->contains("Content-Type", "application/json"));
    }

    public function testUserListPagination(): void
    {

        $client = static::createClient();
        $client->setServerParameter("HTTP_HOST", self::HTTP_HOST);

        $client->request('GET', '/users');

        $response = $client->getResponse();

        $this->assertArrayHasKey("_links", json_decode($response->getContent(), true));
    }

    public function testUserListSearch(): void
    {
        $client = static::createClient();
        $client->setServerParameter("HTTP_HOST", self::HTTP_HOST);

        $client->request('GET', '/users?keyword=client');

        $response = $client->getResponse();

        $arrayResponse = json_decode($response->getContent(), true);

        $this->assertEquals(1, count($arrayResponse['data']));
    }

    public function testUserListPreviousPage(): void
    {

        $client = static::createClient();
        $client->setServerParameter("HTTP_HOST", self::HTTP_HOST);

        $client->request('GET', '/users?page=2');

        $response = $client->getResponse();

        $arrayResponse = json_decode($response->getContent(), true);

        $this->assertArrayHasKey("previous_page", $arrayResponse['_links']);

    }

    public function testUserDetailsPageIsUp(): void
    {

        $client = static::createClient();
        $client->setServerParameter("HTTP_HOST", self::HTTP_HOST);

        $client->request('GET', '/users/1');

        $this->assertResponseStatusCodeSame(200);
    }

    public function testUserPostGoodData(): void
    {
        $client = static::createClient();
        $client->setServerParameter("HTTP_HOST", self::HTTP_HOST);

        $data = '{
            "username": "New user",
            "email": "email@gmail.com"
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
        $client = static::createClient();
        $client->setServerParameter("HTTP_HOST", self::HTTP_HOST);

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
        $client = static::createClient();
        $client->setServerParameter("HTTP_HOST", self::HTTP_HOST);

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

    public function testUserDelete(): void
    {
        $client = static::createClient();
        $client->setServerParameter("HTTP_HOST", self::HTTP_HOST);

        $client->request('DELETE', '/users/15');

        $this->assertResponseStatusCodeSame(204);
    }


}