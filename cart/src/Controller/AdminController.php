<?php

namespace App\Controller;

use App\Service\AdminService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{
    #[Route(
        '/admin/order/{orderId}/status/{statusId}',
        name: 'admin_order_status',
        requirements: ['orderId' => '\d+', 'statusId' => '\d+'],
        methods: ['POST']
    )]
    public function setOrderStatus(
        int $orderId,
        int $statusId,
        AdminService $adminService
    ): JsonResponse
    {
        $order = $adminService->setOrderStatus($orderId, $statusId);
        return $this->json($order);
    }
}
