<?php

namespace App\MessageSerializer;

use App\Message\Product;

final class ProductSerializer extends MessageSerializer
{
    protected string $deserializeType = Product::class;
}
