<?php

namespace App\Service;

use App\Message\Consume\Product\ProductMessage;
use Faker\Factory;
use Symfony\Component\Serializer\SerializerInterface;

readonly class ProductMessageGenerator
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    /**
     * @return ProductDto[]
     */
    public function generateProducts(int $count): array
    {
        $faker = Factory::create();

        $data = [];
        for ($i = 0; $i < $count; $i++) {
            $data[] = [
                'id' => 1, // todo
                'name' => $faker->text(),
                'cost' => $faker->numberBetween(10000, 100000),
                'tax' => $faker->numberBetween(1, 30),
                'version' => 1,
                'measurments' => [
                    'weight' => $faker->numberBetween(1, 100),
                    'height' => $faker->numberBetween(1, 100),
                    'width' => $faker->numberBetween(1, 100),
                    'length' => $faker->numberBetween(1, 100),
                ],
                'description' => $faker->text(500),
            ];
        }

        return $this->serializer->denormalize($data, ProductMessage::class . '[]');
    }
}
