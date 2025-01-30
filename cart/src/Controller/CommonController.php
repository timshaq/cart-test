<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CommonController extends AbstractController
{
    public function __construct(
        protected ValidatorInterface $validator,
        protected SerializerInterface $serializer
    )
    {
    }

    protected function validate($data, $asserts): void
    {
        $errors = $this->validator->validate($data, $asserts);

        if (0 !== $errors->count()) {
            throw new BadRequestException($errors);
        }
    }
}
