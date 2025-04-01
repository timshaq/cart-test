<?php

namespace App\MessageSerializer;

use App\Message\Consume\NewOrder\NewOrderMessage;

final class NewOrderSerializer extends MessageSerializer
{
    protected string $deserializeType = NewOrderMessage::class;
}
