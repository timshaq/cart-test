<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class UserSignUpDto
{
    #[Assert\NotBlank]
    #[Assert\Length(['min' => 6, 'max' => 32])]
    private string $password;
    private ?string $promoId = null;
    #[Assert\AtLeastOneOf([new Assert\Regex('/^\d{10}$/'), new Assert\IsNull()])]
    private ?string $phone = null;
    #[Assert\AtLeastOneOf([new Assert\Email(), new Assert\IsNull()])]
    private ?string $email = null;

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context): void
    {
        if (empty($this->email) && empty($this->phone)) {
            $context->buildViolation('Email or phone is required')
                ->atPath('')
                ->addViolation();
        }
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getPromoId(): ?string
    {
        return $this->promoId;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function setPromoId(?string $promoId): void
    {
        $this->promoId = $promoId;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }
}
