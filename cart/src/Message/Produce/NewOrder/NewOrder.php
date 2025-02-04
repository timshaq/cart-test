<?php

namespace App\Message\Produce\NewOrder;


use App\Message\Message;

final class NewOrder extends Message
{
    public function __construct(
        private string $type,
        private string $notificationType,
        private string $orderNum,
        private NewOrderItems $orderItems,
        private string $deliveryType,
        private NewOrderDeliveryAddress $deliveryAddress,
        private ?string $userPhone = null, // if email type passed
        private ?string $userEmail = null // if sms type passed
    )
    {
        parent::__construct();
    }

    public static function fromArray()
    {

    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getNotificationType(): string
    {
        return $this->notificationType;
    }

    public function setNotificationType(string $notificationType): void
    {
        $this->notificationType = $notificationType;
    }

    public function getOrderNum(): string
    {
        return $this->orderNum;
    }

    public function setOrderNum(string $orderNum): void
    {
        $this->orderNum = $orderNum;
    }

    public function getOrderItems(): NewOrderItems
    {
        return $this->orderItems;
    }

    public function setOrderItems(NewOrderItems $orderItems): void
    {
        $this->orderItems = $orderItems;
    }

    public function getDeliveryType(): string
    {
        return $this->deliveryType;
    }

    public function setDeliveryType(string $deliveryType): void
    {
        $this->deliveryType = $deliveryType;
    }

    public function getDeliveryAddress(): NewOrderDeliveryAddress
    {
        return $this->deliveryAddress;
    }

    public function setDeliveryAddress(NewOrderDeliveryAddress $deliveryAddress): void
    {
        $this->deliveryAddress = $deliveryAddress;
    }

    public function getUserPhone(): ?string
    {
        return $this->userPhone;
    }

    public function setUserPhone(?string $userPhone): void
    {
        $this->userPhone = $userPhone;
    }

    public function getUserEmail(): ?string
    {
        return $this->userEmail;
    }

    public function setUserEmail(?string $userEmail): void
    {
        $this->userEmail = $userEmail;
    }
}
