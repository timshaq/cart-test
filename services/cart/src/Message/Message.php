<?php

namespace App\Message;


use Symfony\Component\Serializer\Attribute\Ignore;

class Message
{
    #[Ignore]
    protected string $messageKey;
    #[Ignore]
    protected array $messageHeaders = [];
    #[Ignore]
    protected ?int $messageOffset = null;
    #[Ignore]
    protected int $messageTimestamp;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->setMessageKey(bin2hex(random_bytes(8)));
        $this->setMessageTimestamp((new \DateTime())->getTimestamp());
    }

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

    public function getMessageOffset(): ?string
    {
        return $this->messageOffset;
    }

    public function setMessageOffset(string $messageOffset): void
    {
        $this->messageOffset = $messageOffset;
    }

    public function getMessageTimestamp(): int
    {
        return $this->messageTimestamp;
    }

    public function setMessageTimestamp(int $messageTimestamp): void
    {
        $this->messageTimestamp = $messageTimestamp;
    }

    public function setMessageData(array $encodedEnvelope): void
    {
        if (isset($encodedEnvelope['key'])) {
            $this->setMessageKey($encodedEnvelope['key']);
        }

        if (isset($encodedEnvelope['headers'])) {
            $this->setMessageHeaders($encodedEnvelope['headers']);
        }

        if (isset($encodedEnvelope['offset'])) {
            $this->setMessageOffset($encodedEnvelope['offset']);
        }

        if (isset($encodedEnvelope['timestamp'])) {
            $this->setMessageTimestamp($encodedEnvelope['timestamp']);
        }
    }
}
