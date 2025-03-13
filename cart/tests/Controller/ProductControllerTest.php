<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class ProductControllerTest extends WebTestCase
{
    private const INVALID_PAGE_LIMIT_VALUES = [
        '',
        'aaa',
        '0',
        '-1',
        'null',
        'true',
        '[]',
        '{}'
    ];

    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/products?page=1&limit=1000');
        self::assertResponseIsSuccessful();
    }

    public function testInvalidPageQueryParameter(): void
    {
        $client = static::createClient();
        foreach (self::INVALID_PAGE_LIMIT_VALUES as $value) {
            $client->request(
                'GET',
                sprintf('/products?page=%s&limit=1000', $value)
            );
            self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        }
    }

    public function testInvalidLimitQueryParameter(): void
    {
        $client = static::createClient();
        foreach (self::INVALID_PAGE_LIMIT_VALUES as $value) {
            $client->request(
                'GET',
                sprintf('/products?page=1&limit=%s', $value)
            );
            self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        }
    }
}
