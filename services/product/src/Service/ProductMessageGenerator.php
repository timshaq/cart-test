<?php

namespace App\Service;

use App\Message\Produce\Product\ProductMessage;
use Faker\Factory;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Serializer\SerializerInterface;

readonly class ProductMessageGenerator
{
    public function __construct(
        private SerializerInterface $serializer,
        private ParameterBagInterface $parameterBag
    )
    {
    }

    /**
     * @return ProductMessage[]
     */
    public function generateProducts(int $count): array
    {
        $faker = Factory::create();

        $data = [];
        for ($i = 0; $i < $count; $i++) {
            $data[] = [
                'id' => $this->generateProductId(),
                'name' => $faker->text(),
                'cost' => $faker->numberBetween(10000, 100000),
                'tax' => $faker->numberBetween(1, 30),
                'version' => 1,
                'measurments' => [
                    'weight' => $faker->numberBetween(1, 100),
                    'height' => $faker->numberBetween(1, 100),
                    'width' => $faker->numberBetween(1, 100),
                    'lenght' => $faker->numberBetween(1, 100),
                ],
                'description' => $faker->text(500),
            ];
        }

        return $this->serializer->denormalize($data, ProductMessage::class . '[]');
    }

    private function generateProductId(): int
    {
        $file = $this->parameterBag->get('kernel.project_dir') . '/var/lastProductId';
        if (file_exists($file)) {
            $lastId = (int) file_get_contents($file);
        } else {
            $lastId = 0;
        }

        $newId = $lastId + 1;
        file_put_contents($file, $newId);

        return $newId;
    }
}
