<?php

namespace App\Normalizer;

use Symfony\Component\HttpFoundation\Response;

class AccessDeniedExceptionNormalizer extends AbstractNormalizer
{
    public function normalize(\Exception $exception)
    {
        $result['code'] = Response::HTTP_FORBIDDEN;

        $result['body'] = [
            'code' => Response::HTTP_FORBIDDEN,
            'message' => $exception->getMessage()
        ];

        return $result;
    }
}
