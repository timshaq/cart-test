<?php

namespace App\Security\ApiKey;

use Symfony\Component\Security\Core\User\UserInterface;

readonly class ApiKeyUser implements UserInterface
{

    public function __construct(private string $apiKey)
    {
    }

    public function getRoles(): array
    {
        return ['ROLE_INTEGRATION'];
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->apiKey;
    }
}
