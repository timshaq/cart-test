<?php

namespace App\Controller;

use App\Service\AdminService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{
    #[Route(
        '/admin/order/{orderId}/status',
        name: 'admin_order_status',
        requirements: ['orderId' => '\d+'],
        methods: ['POST']
    )]
    public function setOrderStatus(
        int $orderId,
        Request $request,
        AdminService $adminService
    ): JsonResponse
    {
        $status = $request->get('status');
        if (empty($status)) {
            throw new BadRequestHttpException('Status cannot be empty');
        }

        $order = $adminService->setOrderStatus($orderId, $status);
        return $this->json($order);
    }
}
