<?php

namespace App\Message\Consume\NewOrder;


final class NewOrderDeliveryAddress
{
    public function __construct(
        private ?string $kladrId = null,
        private ?string $fullAddress = null
    )
    {
    }

    public function getKladrId(): ?string
    {
        return $this->kladrId;
    }

    public function setKladrId(?string $kladrId): void
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
