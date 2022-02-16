<?php

namespace App\Test\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    private const HTTP_HOST = "bilemo.local";

    public function testProductListIsUp(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/products');

        $this->assertResponseStatusCodeSame(200);

    }

    public function testProductListJsonResponse(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/products');

        $response = $client->getResponse();

        $this->assertTrue($response->headers->contains("Content-Type", "application/json"));
    }

    public function testProductListPagination(): void
    {

        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/products');

        $response = $client->getResponse();

        $this->assertArrayHasKey("_links", json_decode($response->getContent(), true));
    }

    public function testProductListSearch(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/products?keyword=0');

        $response = $client->getResponse();

        $arrayResponse = json_decode($response->getContent(), true);

        $this->assertEquals(5, count($arrayResponse['data']));
    }

    public function testProductListPreviousPage(): void
    {

        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/products?page=2');

        $response = $client->getResponse();

        $arrayResponse = json_decode($response->getContent(), true);

        $this->assertArrayHasKey("previous_page", $arrayResponse['_links']);

    }

    public function testProductDetailsPageIsUp(): void
    {

        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/products/1');

        $this->assertResponseStatusCodeSame(200);
    }

    public function testProductDetailsPageContent(): void
    {

        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/products/1');

        $response = $client->getResponse();

        $arrayResponse = json_decode($response->getContent(), true);

        $this->assertEquals('Details for product 1', $arrayResponse['details']);
    }

    public function testProductPostGoodData(): void
    {
        $client = $this->createAuthenticatedClient();

        $data = '{
            "name": "Test product",
            "brand": "Test brand",
            "details": "Test details",
            "releaseDate": "2022-02-18 4:30:30"
        }';

        $client->request(
            'POST',
            '/products',
            [],
            [],
            [],
            $data);

        $this->assertResponseStatusCodeSame(201);
    }

    public function testProductPostBadData(): void
    {
        $client = $this->createAuthenticatedClient();

        $data = '{}';

        $client->request(
            'POST',
            '/products',
            [],
            [],
            [],
            $data);

        $this->assertResponseStatusCodeSame(400);
    }

    public function testProductPatchData(): void
    {

        $client = $this->createAuthenticatedClient();

        $data = '{
            "name": "Product edited"
        }';

        $client->request(
            'PATCH',
            '/products/1',
            [],
            [],
            [],
            $data);

        $this->assertResponseStatusCodeSame(200);
    }

    public function testProductDelete(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('DELETE', '/products/1');

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
