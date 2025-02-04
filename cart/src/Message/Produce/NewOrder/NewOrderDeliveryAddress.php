<?php

namespace App\Message\Produce\NewOrder;


final class NewOrderDeliveryAddress
{
    public function __construct(
        private ?int $kladrId = null,
        private ?string $fullAddress = null
    )
    {
    }

    public function getKladrId(): ?int
    {
        return $this->kladrId;
    }

    public function setKladrId(?int $kladrId): void
    {
        $this->kladrId = $kladrId;
    }

    public function getFullAddress(): ?string
    {
        return $this->fullAddress;
    }

    public function setFullAddress(?string $fullAddress): void
    {
        $this->fullAddress = $fullAddress;
    }
}
