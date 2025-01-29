<?php

namespace App\Message;


class Message
{
    protected string $messageKey;
    protected array $messageHeaders = [];
    protected int $messageOffset;
    protected int $messageTimestamp;

    public function getMessageKey(): string
    {
        return $this->messageKey;
    }

    public function setMessageKey(string $messageKey): void
    {
        $this->messageKey = $messageKey;
    }

    public function getMessageHeaders(): array
    {
        return $this->messageHeaders;
    }

    public function setMessageHeaders(array $messageHeaders): void
    {
        $this->messageHeaders = $messageHeaders;
    }

    public function getMessageOffset(): string
    {
        return $this->messageOffset;
    }

    public function setMessageOffset(string $messageOffset): void
    {
        $this->messageOffset = $messageOffset;
    }

    public function getMessageTimestamp(): string
    {
        return $this->messageTimestamp;
    }

    public function setMessageTimestamp(string $messageTimestamp): void
    {
        $this->messageTimestamp = $messageTimestamp;
    }

    public function setMessageData(array $encodedEnvelope): void
    {
        $this->setMessageKey($encodedEnvelope['key']);
        $this->setMessageHeaders($encodedEnvelope['headers']);
        $this->setMessageOffset($encodedEnvelope['offset']);
        $this->setMessageTimestamp($encodedEnvelope['timestamp']);
    }
}
