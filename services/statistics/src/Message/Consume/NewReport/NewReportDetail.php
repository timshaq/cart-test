<?php

namespace App\Message\Consume\NewReport;


use App\Message\Message;

final class NewReportDetail extends Message
{
    public function __construct(
        private string $error,
        private ?string $message,
    )
    {
        parent::__construct();
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function setError(?string $error): void
    {
        $this->error = $error;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

}
