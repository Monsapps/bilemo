<?php

namespace App\Test\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NotFoundTest extends WebTestCase
{
    private const HTTP_HOST = "bilemo.local";

    public function testNotFoundPageIsDown(): void
    {
        $client = static::createClient();
        $client->setServerParameter("HTTP_HOST", self::HTTP_HOST);

        $client->request('GET', '/');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testNotFoundPageJsonContent(): void
    {
        $client = static::createClient();
        $client->setServerParameter("HTTP_HOST", self::HTTP_HOST);

        $client->request('GET', '/');

        $response = $client->getResponse();

        $this->assertTrue($response->headers->contains("Content-Type", "application/json"));

        $this->assertArrayHasKey("message", json_decode($response->getContent(), true));
    }

    public function testDefaultExceptionNormalizer(): void
    {
        $client = static::createClient();
        $client->setServerParameter("HTTP_HOST", self::HTTP_HOST);

        $client->request('PUT', '/products');

        $this->assertResponseStatusCodeSame(400);
    }
}