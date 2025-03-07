<?php

namespace App\MessageHandler;

use App\Message\Consume\UpdateOrderStatusMessage;
use App\Service\AdminService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class UpdateOrderStatusHandler
{
    public function __construct(private AdminService $adminService)
    {
    }
    public function __invoke(UpdateOrderStatusMessage $message): void
    {
        $this->adminService->setOrderStatus($message->getOrderId(), $message->getStatusId());
    }
}
