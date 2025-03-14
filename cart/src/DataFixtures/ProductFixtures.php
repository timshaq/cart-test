<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\ProductMeasurement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public const REFERENCE_PRODUCT_1 = 'test-product';

    public function load(ObjectManager $manager): void
    {
        $product = new Product();

        $product->setOutId(1);
        $product->setName('Test product');
        $product->setDescription('Description');
        $product->setCost(1000);
        $product->setTax(10);
        $product->setVersion(1);

        $measurement = new ProductMeasurement();
        $measurement->setHeight(1);
        $measurement->setLength(1);
        $measurement->setWeight(100);
        $measurement->setWidth(1);

        $product->setMeasurement($measurement);

        $manager->persist($product);
        $manager->flush();

        $this->addReference(self::REFERENCE_PRODUCT_1, $product);
    }
}
