<?php

namespace App\Service;

use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseService
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    protected function entityValidator($entity): void
    {
        $violations = $this->validator->validate($entity);

        if(count($violations)) {

            throw new ValidationFailedException($entity, $violations);
        }
    }
}