<?php

namespace App\Tests\Dto;

use App\Dto\NewOrderDto;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class NewOrderDtoTest extends KernelTestCase
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
     * @dataProvider validDataProvider
     */
    public function testValidData(string $json): void
    {
        $userSignUp = $this->serializer->deserialize(
            $json,
            NewOrderDto::class,
            'json'
        );

        $violations = $this->validator->validate($userSignUp);

        $this->assertEquals(0, $violations->count());
    }

    public function validDataProvider(): array
    {
        $data = [
            [
                'deliveryType' => 'selfdelivery',
                'deliveryAddress' => null,
                'kladrId' => '01',
            ],
            [
                'deliveryType' => 'courier',
                'deliveryAddress' => 'Moscow, Bug street 13',
                'kladrId' => '99',
            ],
        ];

        return array_map(
            static fn (array $item) => [json_encode($item, JSON_THROW_ON_ERROR)],
            $data
        );
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testInvalidData(string $json): void
    {
        $userSignUp = $this->serializer->deserialize(
            $json,
            NewOrderDto::class,
            'json'
        );

        $violations = $this->validator->validate($userSignUp);

        $this->assertGreaterThan(0, $violations->count());
    }

    public function invalidDataProvider(): array
    {
        $data = [
            [
                'deliveryType' => 'self_delivery',
                'deliveryAddress' => null,
                'kladrId' => '01',
            ],
            [
                'deliveryType' => 'curier',
                'deliveryAddress' => null,
                'kladrId' => '01',
            ],
            [
                'deliveryType' => 'selfdelivery',
                'deliveryAddress' => null,
                'kladrId' => '100',
            ],
        ];

        return array_map(
            static fn (array $item) => [json_encode($item, JSON_THROW_ON_ERROR)],
            $data
        );
    }

    /**
     * @dataProvider invalidTypeDataProvider
     */
    public function testInvalidTypeDataCases(string $json): void
    {
        $this->expectException(NotNormalizableValueException::class);
        $this->serializer->deserialize(
            $json,
            NewOrderDto::class,
            'json'
        );
    }

    public function invalidTypeDataProvider(): array
    {
        $data = [
            [
                'deliveryType' => 'selfdelivery',
                'deliveryAddress' => 1,
                'kladrId' => '01',
            ],
            [
                'deliveryType' => 'selfdelivery',
                'deliveryAddress' => false,
                'kladrId' => '01',
            ],
            [
                'deliveryType' => 'selfdelivery',
                'deliveryAddress' => [],
                'kladrId' => '01',
            ],

            [
                'deliveryType' => 'selfdelivery',
                'deliveryAddress' => null,
                'kladrId' => 99,
            ],
        ];

        return array_map(
            static fn (array $item) => [json_encode($item, JSON_THROW_ON_ERROR)],
            $data
        );
    }
}
