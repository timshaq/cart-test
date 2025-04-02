<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $cartId = null;

    #[ORM\Column(length: 255)]
    private ?string $deliveryType = null;

    #[ORM\Column(length: 4000)]
    private string $orderItems;

    public function getOrderItems(): string
    {
        return $this->orderItems;
    }

    public function setOrderItems(string $orderItems): void
    {
        $this->orderItems = $orderItems;
    }

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $kladrId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fullAddress = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getCartId(): ?string
    {
        return $this->cartId;
    }

    public function setCartId(string $cartId): static
    {
        $this->cartId = $cartId;

        return $this;
    }

    public function getDeliveryType(): ?string
    {
        return $this->deliveryType;
    }

    public function setDeliveryType(string $deliveryType): static
    {
        $this->deliveryType = $deliveryType;

        return $this;
    }

    public function getKladrId(): ?string
    {
        return $this->kladrId;
    }

    public function setKladrId(?string $kladrId): static
    {
        $this->kladrId = $kladrId;

        return $this;
    }

    public function getFullAddress(): ?string
    {
        return $this->fullAddress;
    }

    public function setFullAddress(?string $fullAddress): static
    {
        $this->fullAddress = $fullAddress;

        return $this;
    }
}
