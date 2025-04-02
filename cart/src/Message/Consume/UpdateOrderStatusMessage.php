<?php

namespace App\Message\Consume;


use App\Message\Message;

final class UpdateOrderStatusMessage extends Message
{
    public const ORDER_NUM_PREFIX = 'ORD_';
    public function __construct(
        private readonly string    $orderId,
        private readonly string $status,
    )
    {
        parent::__construct();
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

}
