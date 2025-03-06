<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class NewOrderDto
{
    #[Assert\NotBlank]
    #[Assert\Choice(['selfdelivery', 'courier'])]
    private string $deliveryType;

    private ?string $deliveryAddress;

    private ?int $kladrId;

    public function setDeliveryType(string $deliveryType): void
    {
        $this->deliveryType = $deliveryType;
    }

    public function setDeliveryAddress(?string $deliveryAddress): void
    {
        $this->deliveryAddress = $deliveryAddress;
    }

    public function setKladrId(?int $kladrId): void
    {
        $this->kladrId = $kladrId;
    }

    public function getDeliveryType(): string
    {
        return $this->deliveryType;
    }

    public function getDeliveryAddress(): ?string
    {
        return $this->deliveryAddress;
    }

    public function getKladrId(): ?int
    {
        return $this->kladrId;
    }
}
