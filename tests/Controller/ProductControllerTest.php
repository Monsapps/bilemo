<?php

namespace App\Test\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    private const HTTP_HOST = "bilemo.local";

    public function testProductListIsUp(): void
    {
        $client = static::createClient();
        $client->setServerParameter("HTTP_HOST", self::HTTP_HOST);

        $client->request('GET', '/products');

        $this->assertResponseStatusCodeSame(200);

    }

    public function testProductListJsonResponse(): void
    {
        $client = static::createClient();
        $client->setServerParameter("HTTP_HOST", self::HTTP_HOST);

        $client->request('GET', '/products');

        $response = $client->getResponse();

        $this->assertTrue($response->headers->contains("Content-Type", "application/json"));
    }

    public function testProductListPagination(): void
    {

        $client = static::createClient();
        $client->setServerParameter("HTTP_HOST", self::HTTP_HOST);

        $client->request('GET', '/products');

        $response = $client->getResponse();

        $this->assertArrayHasKey("pagination", json_decode($response->getContent(), true));
    }

    public function testProductListSearch(): void
    {
        $client = static::createClient();
        $client->setServerParameter("HTTP_HOST", self::HTTP_HOST);

        $client->request('GET', '/products?keyword=0');

        $response = $client->getResponse();

        $arrayResponse = json_decode($response->getContent(), true);

        $this->assertEquals(5, count($arrayResponse['data']));
    }

    public function testProductListPreviousPage(): void
    {

        $client = static::createClient();
        $client->setServerParameter("HTTP_HOST", self::HTTP_HOST);

        $client->request('GET', '/products?page=2');

        $response = $client->getResponse();

        $arrayResponse = json_decode($response->getContent(), true);

        $this->assertArrayHasKey("previous_page", $arrayResponse['pagination']);

    }
    
}
