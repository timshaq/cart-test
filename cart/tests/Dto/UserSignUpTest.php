<?php

namespace App\Tests\Dto;

use App\Dto\UserSignUp;
use App\Entity\Constant;
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

    /**
     * @dataProvider positiveCasesProvider
     */
    public function testPositiveCases(string $json): void
    {
        $userSignUp = $this->serializer->deserialize(
            $json,
            UserSignUp::class,
            'json'
        );

        $violations = $this->validator->validate($userSignUp);

        $this->assertEquals(0, $violations->count());
    }

    public function positiveCasesProvider(): array
    {
        $data = [
            [
                'notificationTypeId' => Constant::NOTIFICATION_TYPE_SMS_ID,
                'phone' => '4563251248',
                'password' => '123456',
                'promoId' => null
            ],
            [
                'notificationTypeId' => Constant::NOTIFICATION_TYPE_SMS_ID,
                'phone' => '4563251248',
                'password' => '123456',
                'promoId' => 'promo-id'
            ],
            [
                'notificationTypeId' => Constant::NOTIFICATION_TYPE_EMAIL_ID,
                'email' => 'name@mail.com',
                'password' => '123456',
                'promoId' => null
            ],
            [
                'notificationTypeId' => Constant::NOTIFICATION_TYPE_EMAIL_ID,
                'email' => 'name@mail.com',
                'password' => str_repeat('1', 32),
                'promoId' => 'promo-id'
            ],
        ];

        return array_map(
            static fn (array $item) => [json_encode($item, JSON_THROW_ON_ERROR)],
            $data
        );
    }
}
