<?php

namespace App\Service;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;

class CacheService
{
    const HTTP_CACHE_MAX_AGE = 3600;

    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function getResponse(mixed $data, array $groups = ['Default'], int $statusCode = Response::HTTP_OK, array $headers = null): Response
    {
        $response = new Response($this->serializer->serialize($data, 'json', SerializationContext::create()->setGroups($groups)), $statusCode);

        $response->setPublic();
        $response->setMaxAge(self::HTTP_CACHE_MAX_AGE);
    
        // (optional) set a custom Cache-Control directive
        $response->headers->addCacheControlDirective('must-revalidate', true);

        return $response;
    }

}
