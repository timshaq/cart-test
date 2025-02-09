<?php

namespace App\Entity;

use App\Repository\OrderProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Ignore;

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

    #[ORM\ManyToOne(targetEntity: ProductMeasurement::class)]
    #[ORM\JoinColumn(name: 'measurement_id', referencedColumnName: 'id')]
    private ProductMeasurement $measurement;

    #[Ignore] // todo: check https://symfony.com/doc/current/serializer.html#handling-circular-references
    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private Order $order;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderId(): ?int
    {
        return $this->orderId;
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

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function setOrder(Order $order): void
    {
        $this->order = $order;
    }
}
