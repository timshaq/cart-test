<?php

namespace App\Tests\Dto;

use App\Dto\UserSignUpDto;
use App\Entity\Constant;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\Exception\MissingConstructorArgumentsException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UserSignUpDtoTest extends KernelTestCase
{
    private ValidatorInterface $validator;
    private SerializerInterface $serializer;

    private const VALID_PHONE = '9998887766';
    private const VALID_EMAIL = 'test@mail.com';
    private const VALID_PASSWORD = '123456';

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
            UserSignUpDto::class,
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

    /**
     * @dataProvider requiredConstructCasesProvider
     */
    public function testRequiredConstructCases(string $json): void
    {
        $this->expectException(MissingConstructorArgumentsException::class);
        $this->serializer->deserialize(
            $json,
            UserSignUpDto::class,
            'json'
        );
    }

    public function requiredConstructCasesProvider(): array
    {
        $data = [
            [
                'notificationTypeId' => Constant::NOTIFICATION_TYPE_SMS_ID,
                'phone' => self::VALID_PHONE,
                'promoId' => null
            ],
            [
                'notificationTypeId' => Constant::NOTIFICATION_TYPE_EMAIL_ID,
                'email' => self::VALID_EMAIL,
                'promoId' => null
            ],
            [
                'phone' => self::VALID_PHONE,
                'password' => self::VALID_PASSWORD,
                'promoId' => null
            ],
        ];

        return array_map(
            static fn (array $item) => [json_encode($item, JSON_THROW_ON_ERROR)],
            $data
        );
    }

    /**
     * @dataProvider requiredNullableCasesProvider
     */
    public function testRequiredNullableCases(string $json): void
    {
        $userSignUp = $this->serializer->deserialize(
            $json,
            UserSignUpDto::class,
            'json'
        );

        $violations = $this->validator->validate($userSignUp);

        $this->assertGreaterThan(0, $violations->count());
    }

    public function requiredNullableCasesProvider(): array
    {
        $data = [
            [
                'notificationTypeId' => Constant::NOTIFICATION_TYPE_SMS_ID,
                'password' => self::VALID_PASSWORD,
                'promoId' => null
            ],
            [
                'notificationTypeId' => Constant::NOTIFICATION_TYPE_EMAIL_ID,
                'password' => self::VALID_PASSWORD,
                'promoId' => null
            ],
        ];

        return array_map(
            static fn (array $item) => [json_encode($item, JSON_THROW_ON_ERROR)],
            $data
        );
    }
}
