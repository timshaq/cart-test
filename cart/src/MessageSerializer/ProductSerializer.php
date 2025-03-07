<?php

namespace App\MessageSerializer;

use App\Message\Consume\Product\ProductMessage;

final class ProductSerializer extends MessageSerializer
{
    protected string $deserializeType = ProductMessage::class;
}
