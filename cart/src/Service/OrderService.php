<?php

namespace App\Service;

use App\Dto\NewOrderDto;
use App\Entity\CartItem;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\User;

readonly class OrderService
{
    public function createOrder(User $user, NewOrderDto $newOrderDto): Order
    {
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

        $order->setStatus('оплачен и ждёт сборки');
        $order->setCost($orderCost);
        $order->setUser($user);
        $order->setDeliveryType($newOrderDto->getDeliveryType());
        $order->setDeliveryAddress($newOrderDto->getDeliveryAddress());
        $order->setKladrId($newOrderDto->getKladrId());

        return $order;
    }
}
