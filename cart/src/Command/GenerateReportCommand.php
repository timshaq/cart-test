<?php

namespace App\Command;

use App\Service\KafkaProduceService;
use App\Service\ReportService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Uid\Uuid;
use Throwable;

#[AsCommand(
    name: 'app:generate-report',
    description: 'Add a short description for your command',
)]
class GenerateReportCommand extends Command
{
    public function __construct(
        private readonly ReportService $reportService,
        private readonly KafkaProduceService $kafkaProduceService
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    /**
     * @throws \JsonException
     * @throws ExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $reportId = Uuid::v4();
        $success = true;
        $error = null;

        try {
            $this->reportService->generateCompletedOrdersReport($reportId);
        } catch (Throwable $e) {
            $success = false;
            $error = $e->getMessage();
            dump([
               'trace' => $e->getTrace(),
               'message' => $e->getMessage(),
               'file' => $e->getFile(),
               'line' => $e->getLine()
            ]);
            // todo: logging
        }

        $this->kafkaProduceService->sendNewReport($reportId, $success, $error);

        return $success ? Command::SUCCESS: Command::FAILURE;
    }
}
