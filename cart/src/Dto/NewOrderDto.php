<?php

namespace App\Dto;

use App\Entity\Constant;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

readonly class NewOrderDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Choice(['selfdelivery', 'courier'])]
        private string  $deliveryType,
        private ?string $deliveryAddress = null,
        private ?int    $kladrId = null,
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

    public function getKladrId(): ?int
    {
        return $this->kladrId;
    }
}
