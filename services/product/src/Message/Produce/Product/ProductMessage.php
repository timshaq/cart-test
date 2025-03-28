<?php

namespace App\Message\Produce\Product;

use App\Message\Message;
use Symfony\Component\Serializer\Attribute\SerializedName;

final class ProductMessage extends Message
{
    public function __construct(
        private readonly int                $id,
        private readonly string             $name,
        private readonly int                $cost,
        private readonly int                $tax,
        private readonly int                $version,
        #[SerializedName('measurments')]
        private readonly ProductMeasurement $measurements,
        private readonly ?string            $description = null,
    )
    {
        parent::__construct();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCost(): int
    {
        return $this->cost;
    }

    public function getTax(): int
    {
        return $this->tax;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function getMeasurements(): ProductMeasurement
    {
        return $this->measurements;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
