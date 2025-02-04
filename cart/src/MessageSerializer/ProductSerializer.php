<?php

namespace App\MessageSerializer;

use App\Message\Consume\Product\Product;

final class ProductSerializer extends MessageSerializer
{
    protected string $deserializeType = Product::class;
}
