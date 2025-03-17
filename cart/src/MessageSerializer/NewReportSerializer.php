<?php

namespace App\MessageSerializer;


use App\Message\Produce\NewReport\NewReportMessage;

final class NewReportSerializer extends MessageSerializer
{
    protected string $deserializeType = NewReportMessage::class;
}
