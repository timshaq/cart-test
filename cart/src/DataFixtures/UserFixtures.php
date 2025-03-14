<?php

namespace App\DataFixtures;

use App\Dto\UserSignUpDto;
use App\Entity\Constant;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const REFERENCE_USER = 'REFERENCE_USER';
    public const REFERENCE_USER_WITH_CART_ITEM = 'REFERENCE_USER_WITH_CART_ITEM';
    public const REFERENCE_USER_WITH_LARGE_CART = 'REFERENCE_USER_WITH_LARGE_CART';
    public const REFERENCE_USER_WITH_ORDER = 'REFERENCE_USER_WITH_ORDER';

    private ?ObjectManager $manager = null;

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->loadUser();
        $this->loadUserWithCartItem();
        $this->loadUserWithLargeCart();
        $this->loadUserWithOrder();
    }

    private function loadUser(): void
    {
        $this->createUser(
            self::REFERENCE_USER,
            'user@mail.com'
        );
    }

    private function loadUserWithCartItem(): void
    {
        $this->createUser(
            self::REFERENCE_USER_WITH_CART_ITEM,
            'user-with-cart-item@mail.com'
        );
    }

    private function loadUserWithLargeCart(): void
    {
        $this->createUser(
            self::REFERENCE_USER_WITH_LARGE_CART,
            'user-with-large-cart@mail.com'
        );
    }

    private function loadUserWithOrder(): void
    {
        $this->createUser(
            self::REFERENCE_USER_WITH_ORDER,
            'user-with-order@mail.com'
        );
    }

    private function createUser(string $reference, string $email)
    {
        $userRepository = $this->manager->getRepository(User::class);
        $userSignUpDto = new UserSignUpDto(
            Constant::NOTIFICATION_TYPE_EMAIL_ID,
            '123456',
            null,
            null,
            $email
        );
        $user = $userRepository->createUser($userSignUpDto);

        $this->manager->persist($user);
        $this->manager->flush();

        $this->addReference($reference, $user);
    }
}
