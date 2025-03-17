<?php

namespace App\Message\Produce\NewReport;


use App\Message\Message;

final class NewReportMessage extends Message
{
    public function __construct(
        private string $reportId,
        private string $result,
        private ?NewReportDetail $detail
    )
    {
        parent::__construct();
    }

    public function getReportId(): string
    {
        return $this->reportId;
    }

    public function setReportId(string $reportId): void
    {
        $this->reportId = $reportId;
    }

    public function getResult(): string
    {
        return $this->result;
    }

    public function setResult(string $result): void
    {
        $this->result = $result;
    }

    public function getDetail(): ?NewReportDetail
    {
        return $this->detail;
    }

    public function setDetail(?NewReportDetail $detail): void
    {
        $this->detail = $detail;
    }
}
