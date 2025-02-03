<?php

namespace App\MessageSerializer;

use App\Message\UpdateOrderStatus;

final class UpdateOrderStatusSerializer extends MessageSerializer
{
    protected string $deserializeType = UpdateOrderStatus::class;
}
