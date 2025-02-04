<?php

namespace App\Tests\Dto;

use App\Dto\UserSignUp;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UserSignUpTest extends KernelTestCase
{
    private ValidatorInterface $validator;
    private SerializerInterface $serializer;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->validator = self::getContainer()->get(ValidatorInterface::class);
        $this->serializer = self::getContainer()->get(SerializerInterface::class);
    }

    public function testIndex(): void
    {
        $userSignUpData = [
            'notificationTypeId' => 202,
            'password' => '100500',
            'promoId' => 'asd',
            'phone' => '9523366022',
            'email' => null,
        ];

        $userSignUpData = json_encode($userSignUpData, JSON_THROW_ON_ERROR);

        $userSignUp = $this->serializer->deserialize(
            $userSignUpData,
            UserSignUp::class,
            'json'
        );
//        dump($userSignUp);

        $violations = $this->validator->validate($userSignUp);
        if ($violations->count() > 0) {
            $violation = $violations->get(0);
            $message = $violation->getPropertyPath() . ': ' . $violation->getMessage();
            dd($message);
        }

        dd('ok');
    }
}
