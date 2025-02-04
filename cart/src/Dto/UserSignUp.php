<?php

namespace App\Dto;

use App\Entity\Constant;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class UserSignUp
{
    #[Assert\NotBlank]
    #[Assert\Choice([
        Constant::NOTIFICATION_TYPE_SMS_ID,
        Constant::NOTIFICATION_TYPE_EMAIL_ID
    ])]
    public int $notificationTypeId;

    #[Assert\NotBlank]
    #[Assert\Length(['min' => 6, 'max' => 32])]
    public string $password;

    public ?string $promoId;

    #[Assert\AtLeastOneOf([
        new Assert\Regex('/^\d{10}$/'),
        new Assert\IsNull(),
    ])]
    public ?string $phone = null;

    #[Assert\AtLeastOneOf([
        new Assert\Email(),
        new Assert\IsNull(),
    ])]
    public ?string $email = null;

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context): void
    {
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
