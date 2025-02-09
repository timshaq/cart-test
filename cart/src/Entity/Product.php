<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $outId = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $cost = null;

    #[ORM\Column]
    private ?int $tax = null;

    #[ORM\Column]
    private ?int $version = null;

    #[ORM\Column]
    private ?int $measurementId = null;

    #[ORM\OneToOne(targetEntity: ProductMeasurement::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'measurement_id', referencedColumnName: 'id')]
    private ProductMeasurement $measurement;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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

    public function getVersion(): ?int
    {
        return $this->version;
    }

    public function setVersion(int $version): static
    {
        $this->version = $version;

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

    public function getOutId(): ?int
    {
        return $this->outId;
    }

    public function setOutId(?int $outId): void
    {
        $this->outId = $outId;
    }
}
