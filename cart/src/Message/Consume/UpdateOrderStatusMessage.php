<?php

namespace App\Message\Consume;


use App\Message\Message;

final class UpdateOrderStatusMessage extends Message
{
    public function __construct(
        private readonly int $orderId,
        private readonly int $statusId,
    )
    {
        parent::__construct();
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function getStatusId(): int
    {
        return $this->statusId;
    }

}
