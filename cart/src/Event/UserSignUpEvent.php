<?php

namespace App\Event;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class UserSignUpEvent extends Event
{
    public function __construct(
        private readonly User $user
    )
    {
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
