<?php

namespace App\Entity;

use App\Repository\OrderProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderProductRepository::class)]
class OrderProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $orderId = null;

    #[ORM\Column]
    private ?int $productId = null;

    #[ORM\Column]
    private ?int $measurementId = null;

    #[ORM\Column]
    private ?int $cost = null;

    #[ORM\Column]
    private ?int $tax = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToOne(targetEntity: ProductMeasurement::class)]
    #[ORM\JoinColumn(name: 'measurement_id', referencedColumnName: 'id')]
    private ProductMeasurement $measurement;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getOrderId(): ?int
    {
        return $this->orderId;
    }

    public function setOrderId(int $orderId): static
    {
        $this->orderId = $orderId;

        return $this;
    }

    public function getProductId(): ?int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): static
    {
        $this->productId = $productId;

        return $this;
    }

    public function getCost(): ?int
    {
        return $this->cost;
    }

    public function setCost(int $cost): static
    {
        $this->cost = $cost;

        return $this;
    }

    public function getTax(): ?int
    {
        return $this->tax;
    }

    public function setTax(int $tax): static
    {
        $this->tax = $tax;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getMeasurement(): ProductMeasurement
    {
        return $this->measurement;
    }

    public function setMeasurement(ProductMeasurement $measurement): void
    {
        $this->measurement = $measurement;
    }
}
