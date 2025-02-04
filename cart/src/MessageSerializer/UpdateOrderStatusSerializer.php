<?php

namespace App\MessageSerializer;

use App\Message\Consume\UpdateOrderStatus;

final class UpdateOrderStatusSerializer extends MessageSerializer
{
    protected string $deserializeType = UpdateOrderStatus::class;
}
