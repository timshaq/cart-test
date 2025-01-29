<?php

namespace App\Message;

use Symfony\Component\Serializer\Attribute\SerializedName;

final class Product
{
     public function __construct(
         public int $id,
         public string $name,
         public int $cost,
         public int $tax,
         public int $version,
         #[SerializedName('measurments')]
         public ProductMeasurement $measurements,
         public ?string $description = null,
     )
     {
     }
}
