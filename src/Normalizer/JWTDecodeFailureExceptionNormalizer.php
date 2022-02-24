<?php

namespace App\Normalizer;

use Symfony\Component\HttpFoundation\Response;

class JWTDecodeFailureExceptionNormalizer extends AbstractNormalizer
{
    public function normalize(\Exception $exception)
    {
        $result['code'] = Response::HTTP_UNAUTHORIZED;

        $result['body'] = [
            'code' => Response::HTTP_UNAUTHORIZED,
            'message' => $exception->getMessage()
        ];

        return $result;
    }
}
