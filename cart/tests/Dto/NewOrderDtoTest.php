<?php

namespace App\Tests\Dto;

use App\Dto\NewOrderDto;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
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
     * @dataProvider positiveCasesProvider
     */
    public function testPositiveCases(string $json): void
    {
        $userSignUp = $this->serializer->deserialize(
            $json,
            NewOrderDto::class,
            'json'
        );

        $violations = $this->validator->validate($userSignUp);

        $this->assertEquals(0, $violations->count());
    }

    public function positiveCasesProvider(): array
    {
        $deliveryTypes = [
            'selfdelivery',
            'courier'
        ];
        $deliveryAddresses = [
            null,
            '',
            ' ',
            '           ',
            'Moscow, Bug street 13'
        ];
        // todo: check kladr, it string - '01' for example
        $kladrIds = [
            1, 59, 77, 99
        ];

        // todo: mix data
        $data = [];

        return array_map(
            static fn (array $item) => [json_encode($item, JSON_THROW_ON_ERROR)],
            $data
        );
    }


}
