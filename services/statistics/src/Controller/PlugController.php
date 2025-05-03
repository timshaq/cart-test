<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PlugController extends AbstractController
{
    #[Route('/plug/report', name: 'plug-report', methods: ['GET'])]
    public function sendReportRequest(HttpClientInterface $client): Response
    {
        try {
            $response = $client->request(
                'GET',
                'http://cart-service:8410/api/integration/report/orders/completed',
                [
                    'headers' => ['api-key' => $this->getParameter('api.key.cart')]
                ]
            );

            if ($response->getStatusCode() !== 200) {
                throw new \RuntimeException('Unexpected service error: ' . $response->getContent());
            }
        } catch (\Throwable $exception) {
            throw new \RuntimeException('Unexpected service error: ' . $exception->getMessage());
        }

        return new Response();
    }
}
