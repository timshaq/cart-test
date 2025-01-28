<?php

namespace App\Message;

final class Product
{
     public function __construct(
         public int $id,
         public string $name,
         public int $cost,
         public int $tax,
         public int $version,
         public array $measurements,
         public ?string $description = null,
     ) {
     }
     public static function fromArray(array $array): self
     {
         return new self(
             $array['id'],
             $array['name'],
             $array['cost'],
             $array['tax'],
             $array['version'],
             $array['measurments'],
             $array['description'],
         );
     }
}
