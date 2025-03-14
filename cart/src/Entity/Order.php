<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Ignore;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    public const CART_MAX_ITEMS = 20;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $userId = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private \DateTimeInterface $date;

    #[ORM\Column]
    private ?int $statusId = null;

    #[ORM\ManyToOne(targetEntity: Constant::class)]
    #[ORM\JoinColumn(name: 'status_id', referencedColumnName: 'id')]
    private ?Constant $status = null;

    #[ORM\Column]
    private ?int $cost = null;

    #[ORM\OneToMany(targetEntity: OrderProduct::class, mappedBy: 'order', cascade: ['persist', 'remove'])]
    private Collection $products;

    #[Ignore] // todo: check https://symfony.com/doc/current/serializer.html#handling-circular-references
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\Column(nullable: true)]
    private ?int $kladrId = null;

    #[ORM\Column]
    private string $deliveryType;

    #[ORM\Column(nullable: true)]
    private ?string $deliveryAddress = null;

    public function __construct()
    {
        $this->date = new \DateTimeImmutable();
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function setProducts(Collection $products): void
    {
        $this->products = $products;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function getStatus(): ?Constant
    {
        return $this->status;
    }

    public function setStatus(?Constant $status): void
    {
        $this->status = $status;
    }

    public function getKladrId(): ?int
    {
        return $this->kladrId;
    }

    public function getDeliveryType(): string
    {
        return $this->deliveryType;
    }

    public function getDeliveryAddress(): ?string
    {
        return $this->deliveryAddress;
    }

    public function setKladrId(?int $kladrId): void
    {
        $this->kladrId = $kladrId;
    }

    public function setDeliveryType(string $deliveryType): void
    {
        $this->deliveryType = $deliveryType;
    }

    public function setDeliveryAddress(?string $deliveryAddress): void
    {
        $this->deliveryAddress = $deliveryAddress;
    }
}
