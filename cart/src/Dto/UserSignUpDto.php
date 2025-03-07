<?php

namespace App\Dto;

use App\Entity\Constant;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

readonly class UserSignUpDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Choice([
            Constant::NOTIFICATION_TYPE_SMS_ID,
            Constant::NOTIFICATION_TYPE_EMAIL_ID
        ])]
        private int $notificationTypeId,

        #[Assert\NotBlank]
        #[Assert\Length(['min' => 6, 'max' => 32])]
        private string $password,

        private ?string $promoId = null,

        #[Assert\AtLeastOneOf([new Assert\Regex('/^\d{10}$/'), new Assert\IsNull()])]
        private ?string $phone = null,

        #[Assert\AtLeastOneOf([new Assert\Email(), new Assert\IsNull()])]
        private ?string $email = null,
    )
    {
    }

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context): void
    {
        if (!isset($this->notificationTypeId)) {
            $context->buildViolation('The field is required.')
                ->atPath('notification type id')
                ->addViolation();
        } else {
            if (
                $this->notificationTypeId === Constant::NOTIFICATION_TYPE_SMS_ID &&
                $this->phone === null
            ) {
                $context->buildViolation('The field is required.')
                    ->atPath('phone')
                    ->addViolation();
            }

            if (
                $this->notificationTypeId === Constant::NOTIFICATION_TYPE_EMAIL_ID &&
                $this->email === null
            ) {
                $context->buildViolation('The field is required.')
                    ->atPath('email')
                    ->addViolation();
            }
        }

    }

    public function getNotificationTypeId(): int
    {
        return $this->notificationTypeId;
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
}
