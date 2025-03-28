<?php

namespace App\Message\Produce;


use App\Message\Message;

final class UserSignUpMessage extends Message
{
    // todo: only $userPhone or $userEmail
    public function __construct(
        private readonly string  $type,
        private readonly ?string $userPhone = null,
        private readonly ?string $userEmail = null,
        private readonly ?string $promoId = null,
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
