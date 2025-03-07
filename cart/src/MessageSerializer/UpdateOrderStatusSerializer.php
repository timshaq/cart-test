<?php

namespace App\MessageSerializer;

use App\Message\Consume\UpdateOrderStatusMessage;

final class UpdateOrderStatusSerializer extends MessageSerializer
{
    protected string $deserializeType = UpdateOrderStatusMessage::class;
}
