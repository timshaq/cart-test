<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\ProductMeasurement;
use App\Message\Consume\Product\Product as MessageProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function createOrUpdateByMessage(MessageProduct $messageProduct): void
    {
        $this->getEntityManager()->beginTransaction();

        try {
            $product = $this->findOneBy(['outId' => $messageProduct->getId()]);
            if (!$product) {
                $product = new Product();
                $product->setMeasurement(new ProductMeasurement());
            }
            $product->setOutId($messageProduct->getId());
            $product->setName($messageProduct->getName());
            $product->setDescription($messageProduct->getDescription());
            $product->setCost($messageProduct->getCost());
            $product->setTax($messageProduct->getTax());
            $product->setVersion($messageProduct->getVersion());

            // todo: split the logic
            $product->getMeasurement()->setHeight($messageProduct->getMeasurements()->getHeight());
            $product->getMeasurement()->setLength($messageProduct->getMeasurements()->getLength());
            $product->getMeasurement()->setWeight($messageProduct->getMeasurements()->getWeight());
            $product->getMeasurement()->setWidth($messageProduct->getMeasurements()->getWidth());

            $this->getEntityManager()->persist($product);
            $this->getEntityManager()->flush();
            $this->getEntityManager()->commit();
        } catch (\Throwable) {
            $this->getEntityManager()->rollback();
            // todo: bug: endless produce message to topic
            throw new \RuntimeException('Can\'t create or update Product');
        }
    }
}
