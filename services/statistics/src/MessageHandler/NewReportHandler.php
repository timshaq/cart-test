<?php

namespace App\MessageHandler;

use App\Message\Consume\NewReport\NewReportMessage;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsMessageHandler]
final readonly class NewReportHandler
{
    public function __construct(
        private HttpClientInterface $client,
        private ParameterBagInterface $parameterBag
    )
    {
    }
    public function __invoke(NewReportMessage $message): void
    {
        dump($message);
        if ($message->getResult() === 'success') {
            try {
                $response = $this->client->request(
                    'GET',
                    'http://cart-service:8410/api/integration/report/' . $message->getReportId(),
                    [
                        'headers' => ['api-key' => $this->parameterBag->get('api.key.cart')]
                    ]
                );

                if ($response->getStatusCode() !== 200) {
                    throw new \RuntimeException('Unexpected service error: ' . $response->getContent());
                }

                $putResult = file_put_contents(
                    $this->parameterBag->get('kernel.project_dir') . '/var/reports' . $message->getReportId() . '.jsonl',
                    $response->getContent()
                );

                if ($putResult === false) {
                    throw new \RuntimeException('Unable to write jsonl file');
                }

            } catch (\Throwable $exception) {
                throw new \RuntimeException('Unexpected service error: ' . $exception->getMessage());
            }
        }
    }
}
