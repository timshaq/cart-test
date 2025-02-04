<?php

namespace App\Message\Consume\Product;

use Symfony\Component\Serializer\Attribute\SerializedName;

final class ProductMeasurement
{
     public function __construct(
         private int $weight,
         private int $height,
         private int $width,
         #[SerializedName('lenght')]
         private int $length,
     )
     {
     }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getLength(): int
    {
        return $this->length;
    }
}
