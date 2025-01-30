<?php

namespace App\Message;


final class UserSignUp extends Message
{
    public function __construct(
        private string $type,
        private ?string $userPhone = null,
        private ?string $userEmail = null,
        private ?string $promoId = null,
    )
    {
        parent::__construct();
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getUserPhone(): ?string
    {
        return $this->userPhone;
    }

    public function getUserEmail(): ?string
    {
        return $this->userEmail;
    }

    public function getPromoId(): ?string
    {
        return $this->promoId;
    }
}
