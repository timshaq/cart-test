<?php

namespace App\Tests\Controller;

use App\DataFixtures\UserFixtures;
use App\Entity\User;
use App\Tests\UserWebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class OrderControllerTest extends UserWebTestCase
{
    // todo: refactor it (move to common method)
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

    public function testUnauthorized(): void
    {
        $this->client->request('GET', '/orders');
        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testAuthorized(): void
    {
        $user = $this->referenceRepository->getReference(
            UserFixtures::REFERENCE_USER,
            User::class
        );

        $this->assertNotNull($user);

        $this->client->loginUser($user);
        $this->client->request('GET', '/orders');

        $this->assertResponseIsSuccessful();
    }

    // todo: fix multiple requests
//    public function testInvalidPageQueryParameter(): void
//    {
//        $userRepository = $this->entityManager->getRepository(User::class);
//        $user = $userRepository->findOneBy(['email' => UserFixtures::USER_EMAIL]);
//
//        $this->assertNotNull($user);
//        $this->client->loginUser($user);
//
//        foreach (self::INVALID_PAGE_LIMIT_VALUES as $value) {
//            $this->client->request(
//                'GET',
//                sprintf('/orders?page=%s&limit=1000', $value)
//            );
//            self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
//        }
//    }

//    public function testInvalidLimitQueryParameter(): void
//    {
//        // todo: refactor it (move to method)
//        $userRepository = $this->entityManager->getRepository(User::class);
//        $user = $userRepository->findOneBy(['email' => UserFixtures::USER_EMAIL]);
//
//        $this->assertNotNull($user);
//
//        $this->client->loginUser($user);
//
//        foreach (self::INVALID_PAGE_LIMIT_VALUES as $value) {
//            $this->client->request(
//                'GET',
//                sprintf('/orders?page=1&limit=%s', $value)
//            );
//            self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
//        }
//    }
}
