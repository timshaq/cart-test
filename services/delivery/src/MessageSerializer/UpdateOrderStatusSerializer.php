<?php

namespace App\MessageSerializer;

use App\Message\Produce\UpdateOrderStatusMessage;

final class UpdateOrderStatusSerializer extends MessageSerializer
{
    protected string $deserializeType = UpdateOrderStatusMessage::class;
}
