<?php

namespace App\Message;

use Symfony\Component\Serializer\Attribute\SerializedName;

final class ProductMeasurement
{
     public function __construct(
         public int $weight,
         public int $height,
         public int $width,
         #[SerializedName('lenght')]
         public int $length,
     )
     {
     }
}
