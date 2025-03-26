<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

readonly class NewOrderDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Choice(['selfdelivery', 'courier'])]
        private string  $deliveryType,
        private ?string $deliveryAddress = null,
        #[Assert\AtLeastOneOf([new Assert\Regex('/^\d{2}$/'), new Assert\IsNull()])]
        private ?string $kladrId = null,
    )
    {
    }

    public function getDeliveryType(): string
    {
        return $this->deliveryType;
    }

    public function getDeliveryAddress(): ?string
    {
        return $this->deliveryAddress;
    }

    public function getKladrId(): ?string
    {
        return $this->kladrId;
    }
}
