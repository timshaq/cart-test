<?php

namespace App\Message;


final class UpdateOrderStatus extends Message
{
    public function __construct(
        private int $orderId,
        private string $statusId,
    )
    {
        parent::__construct();
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function getStatusId(): string
    {
        return $this->statusId;
    }

}
