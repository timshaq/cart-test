<?php

namespace App\Message\Produce;


use App\Message\Message;

final class UpdateOrderStatusMessage extends Message
{
    public function __construct(
        private readonly int $orderId,
        private readonly string $status,
    )
    {
        parent::__construct();
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

}
