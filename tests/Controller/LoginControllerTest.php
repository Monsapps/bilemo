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

    public function testExpiredJwtToken(): void
    {
        $oldJwt = "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2NDUyNjcwNjQsImV4cCI6MTY0NTI3MDY2NCwicm9sZXMiOlsiUk9MRV9CSUxFTU8iLCJST0xFX1VTRVIiXSwidXNlcm5hbWUiOiJhZG1pbiJ9.CKoHMYlKh8YLEmnH-7N4zyS_1SMEe78bcG4-MM6beOXBHtr2kZjyCJ3CERCIOqBlOEQqJ_H9oK1j9w5TKlIQgp6V4rmyngWMpGTZyK4iuNXHE6C-Oi1zzx5ddTH6d-kNodQmA9YV0FRJ5XPAnGcdN-6qr6c8ccut3kBjo5KwkmnSUK91ad2tbArVw-FKEJKeFQ4V1zOjPOBeu6v14hBDZncse0NFC6PocbQfqVTaGzuxaKON-aO--EVDjmVlZD7sYzF_LVrc3Zgki96iw-mi8ZOcOS671PtEGBzGzd1mM9slP0bc8KYCO8sBxkP6nk5eHZ9eyDH7wgciPJaKeSk-ez-meVkaOts-vI0DiruxQyaVOUuDq3mTGd_rwEOB9wY4PubRlHTX2t_GS981cG-lyKlIKSTufctMI6JcCBnKrnG2FVlMYuBwi5ibXXwjxlARVM8Effbym-UGvDvjCeyVX-oTjrjCr7U55FJ3kXz5z82dXygiIeYRsESRZ4daCjpemsXsrViHNV-5hKeRDuQRncmG8sbPC-pGgYH-n5h5z-UfSAJpx66xtwNqkHBzQdtjN4HFkmGNZ3LMxV3hQD79Ptd9RQ_RvtddLZ8ElcDiVf1PZlrSH7G1aTLz4WrYd1xfB9tqlCgZ2n_-wVIgVTi8UO8DbFskd0jPNPOrE1taukQ";
        $client = static::createClient([], [
            'HTTP_HOST' => self::HTTP_HOST,
            'CONTENT_TYPE' => 'application/ld+json',
            'HTTP_ACCEPT' => 'application/ld+json',
            'HTTP_AUTHORIZATION' => $oldJwt
        ]);

        $client->request('GET', '/products');

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertResponseStatusCodeSame(401);

        $this->assertEquals('Expired JWT Token', $response['message']);
    }

    public function testInvalidJwtToken(): void
    {
        $invalidJwt = "Bearer xxx";
        $client = static::createClient([], [
            'HTTP_HOST' => self::HTTP_HOST,
            'CONTENT_TYPE' => 'application/ld+json',
            'HTTP_ACCEPT' => 'application/ld+json',
            'HTTP_AUTHORIZATION' => $invalidJwt
        ]);

        $client->request('GET', '/products');

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertResponseStatusCodeSame(401);

        $this->assertEquals('Invalid JWT Token', $response['message']);
    }
}