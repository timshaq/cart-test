<?php

namespace App\Service;

use App\Dto\NewOrderDto;
use App\Entity\CartItem;
use App\Entity\Constant;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;

readonly class OrderService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
    }

    private function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    /**
     * @throws ORMException
     */
    public function createOrder(User $user, NewOrderDto $newOrderDto): Order
    {
        $paidOrderStatus = $this->getEntityManager()->getReference(
            Constant::class,
            Constant::ORDER_STATUS_PAID_ID
        );

        $this->getEntityManager()->beginTransaction();

        try {
            $order = new Order();
            $orderCost = 0;
            foreach ($user->getCartItems() as $cartItem) {
                /** @var CartItem $cartItem */
                $orderCost += $cartItem->getProduct()->getCost();

                $cartItemProduct = $cartItem->getProduct();

                $orderProduct = new OrderProduct();
                $orderProduct->setCost($cartItemProduct->getCost());
                $orderProduct->setProductId($cartItemProduct->getId());
                $orderProduct->setMeasurement($cartItemProduct->getMeasurement());
                $orderProduct->setName($cartItemProduct->getName());
                $orderProduct->setTax($cartItemProduct->getTax());
                $orderProduct->setOrder($order);

                $order->getProducts()->add($orderProduct);
            }

            $order->setStatus($paidOrderStatus);
            $order->setCost($orderCost);
            $order->setUser($user);
            $order->setDeliveryType($newOrderDto->getDeliveryType());
            $order->setDeliveryAddress($newOrderDto->getDeliveryAddress());
            $order->setKladrId($newOrderDto->getKladrId());

            $user->setCartItems(new ArrayCollection());

            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->persist($order);

            $this->getEntityManager()->flush();
            $this->getEntityManager()->commit();
        } catch (\Throwable $e) {
            dump($e->getMessage());
            $this->getEntityManager()->rollback();
            throw new \RuntimeException($e->getMessage(), $e->getCode(), [$e]);
        }

        return $order;
    }
}
