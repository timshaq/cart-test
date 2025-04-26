<?php

namespace App\Tests\Dto;

use App\Dto\UserSignUpDto;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UserSignUpDtoTest extends KernelTestCase
{
    private ValidatorInterface $validator;
    private SerializerInterface $serializer;

    private const VALID_PHONE = '9998887766';
    private const VALID_EMAIL = 'test@mail.com';
    private const VALID_PASSWORD = '123456';
    private const VALID_PROMO_ID = 'promo-id';

    protected function setUp(): void
    {
        self::bootKernel();
        $this->validator = self::getContainer()->get(ValidatorInterface::class);
        $this->serializer = self::getContainer()->get(SerializerInterface::class);
    }

    /**
     * @dataProvider positiveCasesProvider
     * @throws ExceptionInterface
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
                'phone' => self::VALID_PHONE,
                'password' => self::VALID_PASSWORD,
                'promoId' => null
            ],
            [
                'phone' => self::VALID_PHONE,
                'password' => self::VALID_PASSWORD,
                'promoId' => self::VALID_PROMO_ID
            ],
            [
                'email' => 'name@mail.com',
                'password' => self::VALID_PASSWORD,
                'promoId' => null
            ],
            [
                'email' => 'name@mail.com',
                'password' => str_repeat('1', 32),
                'promoId' => self::VALID_PROMO_ID
            ],
        ];

        return array_map(
            static fn (array $item) => [json_encode($item, JSON_THROW_ON_ERROR)],
            $data
        );
    }

    /**
     * @dataProvider nullRequiredParamsProvider
     */
    public function testNullRequiredParams(string $json): void
    {
        $userSignUpDto = $this->serializer->deserialize(
            $json,
            UserSignUpDto::class,
            'json'
        );

        $violations = $this->validator->validate($userSignUpDto);
        $this->assertGreaterThanOrEqual(1, $violations->count());
    }

    public function nullRequiredParamsProvider(): array
    {
        $data = [
            [
                'phone' => self::VALID_PHONE,
                'promoId' => null
            ],
            [
                'email' => self::VALID_EMAIL,
                'promoId' => null
            ],
            [
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
