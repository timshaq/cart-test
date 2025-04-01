<?php

namespace App\Service;

use App\Entity\Order;
use App\Repository\ConstantRepository;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

readonly class AdminService
{
    public function __construct(
        private OrderRepository        $orderRepository,
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function setOrderStatus(int $orderId, string $status): Order
    {
        $order = $this->orderRepository->find($orderId);
        if (!$order) {
            throw new BadRequestException('Order not found');
        }

        $order->setStatus($status);
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $order;
    }

}
