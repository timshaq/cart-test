<?php

namespace App\MessageHandler;

use App\Message\UpdateOrderStatus;
use App\Service\AdminService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class UpdateOrderStatusHandler
{
    public function __construct(private AdminService $adminService)
    {
    }
    public function __invoke(UpdateOrderStatus $message): void
    {
        $this->adminService->setOrderStatus($message->getOrderId(), $message->getStatusId());
    }
}
