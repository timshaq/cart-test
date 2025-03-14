<?php

namespace App\DataFixtures;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\ProductMeasurement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    private const REFERENCE_PRODUCT_PREFIX = 'test-product-';
    private ?ObjectManager $manager = null;

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        for ($i = 0; $i < Order::CART_MAX_ITEMS + 1; $i++) {
            $this->loadProducts($i + 1, uniqid());
        }
    }

    private function loadProducts(int $outId, string $productName): void
    {
        $product = new Product();

        $product->setOutId($outId);
        $product->setName($productName);
        $product->setDescription('Description for test product');
        $product->setCost(1000);
        $product->setTax(10);
        $product->setVersion(1);

        $measurement = new ProductMeasurement();
        $measurement->setHeight(1);
        $measurement->setLength(1);
        $measurement->setWeight(100);
        $measurement->setWidth(1);

        $product->setMeasurement($measurement);

        $this->manager->persist($product);
        $this->manager->flush();

        $this->addReference(
            self::REFERENCE_PRODUCT_PREFIX . $productName,
            $product
        );
    }
}
