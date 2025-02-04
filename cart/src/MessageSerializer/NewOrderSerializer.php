<?php

namespace App\MessageSerializer;

use App\Message\Produce\NewOrder\NewOrder;

final class NewOrderSerializer extends MessageSerializer
{
    protected string $deserializeType = NewOrder::class;
}
