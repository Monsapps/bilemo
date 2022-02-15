<?php

namespace App\Normalizer;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ConstraintValidationExceptionNormalizer extends AbstractNormalizer
{
    public function normalize(\Exception $exception)
    {
        $result = [];

        if($exception instanceof ValidationFailedException) {

            $result['code'] = Response::HTTP_BAD_REQUEST;

            $result['body']['code'] = Response::HTTP_BAD_REQUEST;

            foreach ($exception->getViolations() as $violation) {
                $result['body']['error_message'][$violation->getPropertyPath()] =  $violation->getMessage();
            }

        }

        return $result;
    }
}