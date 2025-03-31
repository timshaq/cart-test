<?php

namespace App\MessageSerializer;


use App\Message\Produce\Product\ProductMessage;

final class ProductSerializer extends MessageSerializer
{
    protected string $deserializeType = ProductMessage::class;
}
