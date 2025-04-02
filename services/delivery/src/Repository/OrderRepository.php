<?php

namespace App\Repository;

use App\Entity\Order;
use App\Message\Consume\NewOrder\NewOrderMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Throwable;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * @throws \JsonException
     */
    public function createNewOrder(
        string $cartId,
        string $deliveryType,
        array $orderItems,
        ?string $fullAddress = null,
        ?string $kladrId = null,
    ): void
    {
        $this->getEntityManager()->beginTransaction();
        try {
            $order = new Order();
            $order->setCartId($cartId);
            $order->setDeliveryType($deliveryType);
            $order->setOrderItems(json_encode($orderItems, JSON_THROW_ON_ERROR));
            $order->setFullAddress($fullAddress);
            $order->setKladrId($kladrId);

            $this->getEntityManager()->persist($order);
            $this->getEntityManager()->flush();
            $this->getEntityManager()->commit();
        } catch (Throwable $e) {
            $this->getEntityManager()->rollback();
        }
    }
}
