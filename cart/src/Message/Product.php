<?php

namespace App\Message;

use Symfony\Component\Serializer\Attribute\SerializedName;

final class Product extends Message
{
    public function __construct(
        private int $id,
        private string $name,
        private int $cost,
        private int $tax,
        private int $version,
        #[SerializedName('measurments')]
        private ProductMeasurement $measurements,
        private ?string $description = null,
    )
    {
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
