<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\ProductMeasurement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Message\Product as MessageProduct;

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
            $product = $this->find($messageProduct->getId());
            // todo: why consumer become producer if next line ($product)
            if (!$product) {
                $product = new Product();
                $product->setMeasurement(new ProductMeasurement());
            }
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
            $this->getEntityManager()->persist($product->getMeasurement());
            $this->getEntityManager()->flush();
        } catch (\Throwable $e) {
            $this->getEntityManager()->rollback();
            throw new \RuntimeException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }

        $this->getEntityManager()->commit();
    }
}
