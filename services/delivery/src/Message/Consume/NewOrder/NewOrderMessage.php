<?php

namespace App\Message\Consume\NewOrder;


use App\Message\Message;

final class NewOrderMessage extends Message
{
    public function __construct(
        private string                  $orderNum,
        private array                   $orderItems,
        private string                  $deliveryType,
        private NewOrderDeliveryAddress $deliveryAddress,
    )
    {
        parent::__construct();
    }

    public function getOrderNum(): string
    {
        return $this->orderNum;
    }

    public function setOrderNum(string $orderNum): void
    {
        $this->orderNum = $orderNum;
    }

    public function getOrderItems(): array
    {
        return $this->orderItems;
    }

    public function setOrderItems(array $orderItems): void
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
}
