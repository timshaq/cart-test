<?php

namespace App\Tests\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class AuthControllerTest extends WebTestCase
{
    protected ?KernelBrowser $client = null;
    protected ?EntityManagerInterface $entityManager = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        if ($this->entityManager) {
            $this->entityManager->close();
            $this->entityManager = null;
        }
    }

    public function testPhone(): void
    {
        $this->client->request(
            'POST',
            '/sign-up',
            [
                'phone' => '4563251248',
                'password' => '123456',
                'promoId' => null
            ]
        );

        self::assertResponseIsSuccessful();
    }

    public function testInvalidPhone(): void
    {
        $this->client->request(
            'POST',
            '/sign-up',
            [
                'phone' => '45632512481',
                'password' => '123456',
                'promoId' => null
            ]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testNullPhone(): void
    {
        $this->client->request(
            'POST',
            '/sign-up',
            [
                'phone' => null,
                'password' => '123456',
                'promoId' => null
            ]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testEmptyPhone(): void
    {
        $this->client->request(
            'POST',
            '/sign-up',
            [
                'phone' => "",
                'password' => '123456',
                'promoId' => null
            ]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testWithoutPhone(): void
    {
        $this->client->request(
            'POST',
            '/sign-up',
            [
                'password' => '123456',
                'promoId' => null
            ]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testEmailNotificationTypeWithPhone(): void
    {
        $this->client->request(
            'POST',
            '/sign-up',
            [
                'phone' => "9999999999",
                'password' => '123456',
                'promoId' => null
            ]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testEmailNotificationTypeWithPhoneButEmail(): void
    {
        $this->client->request(
            'POST',
            '/sign-up',
            [
                'phone' => "test@mail.com",
                'password' => '123456',
                'promoId' => null
            ]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testEmail(): void
    {
        $this->client->request(
            'POST',
            '/sign-up',
            [
                'email' => 'test@mail.com',
                'password' => '123456',
                'promoId' => null
            ]
        );

        self::assertResponseIsSuccessful();
    }

    public function testInvalidEmail(): void
    {
        $this->client->request(
            'POST',
            '/sign-up',
            [
                'email' => 'testmail.com',
                'password' => '123456',
                'promoId' => null
            ]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testNullEmail(): void
    {
        $this->client->request(
            'POST',
            '/sign-up',
            [
                'email' => null,
                'password' => '123456',
                'promoId' => null
            ]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testEmptyEmail(): void
    {
        $this->client->request(
            'POST',
            '/sign-up',
            [
                'email' => "",
                'password' => '123456',
                'promoId' => null
            ]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testWithoutEmail(): void
    {
        $this->client->request(
            'POST',
            '/sign-up',
            [
                'password' => '123456',
                'promoId' => null
            ]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testSmsNotificationTypeWithEmail(): void
    {
        $this->client->request(
            'POST',
            '/sign-up',
            [
                'email' => "test@mail.com",
                'password' => '123456',
                'promoId' => null
            ]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testSmsNotificationTypeWithEmailButPhone(): void
    {
        $this->client->request(
            'POST',
            '/sign-up',
            [
                'email' => "9999999999",
                'password' => '123456',
                'promoId' => null
            ]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testNullPasswordWithPhone(): void
    {
        $this->client->request(
            'POST',
            '/sign-up',
            [
                'phone' => '9999999999',
                'password' => null,
                'promoId' => null
            ]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testEmptyPasswordWithPhone(): void
    {
        $this->client->request(
            'POST',
            '/sign-up',
            [
                'phone' => '9999999999',
                'password' => "",
                'promoId' => null
            ]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testWithoutPasswordWithPhone(): void
    {
        $this->client->request(
            'POST',
            '/sign-up',
            [
                'email' => 'test@mail.com',
                'promoId' => null
            ]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testNullPasswordWithEmail(): void
    {
        $this->client->request(
            'POST',
            '/sign-up',
            [
                'email' => 'test@mail.com',
                'password' => null,
                'promoId' => null
            ]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testEmptyPasswordWithEmail(): void
    {
        $this->client->request(
            'POST',
            '/sign-up',
            [
                'email' => 'test@mail.com',
                'password' => "",
                'promoId' => null
            ]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testWithoutPasswordWithEmail(): void
    {
        $this->client->request(
            'POST',
            '/sign-up',
            [
                'email' => 'test@mail.com',
                'promoId' => null
            ]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
