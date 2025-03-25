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
        private ConstantRepository     $constantRepository,
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function setOrderStatus($orderId, $statusId): Order
    {
        $order = $this->orderRepository->find($orderId);
        if (!$order) {
            throw new BadRequestException('Order not found');
        }

        $status = $this->constantRepository->find($statusId);
        if (!$status) {
            throw new BadRequestException('Status not found');
        }

        $order->setStatus($status);
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $order;
    }

}
