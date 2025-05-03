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
    public const REFERENCE_USER_WITH_ADMIN_ROLE = 'REFERENCE_USER_WITH_ADMIN_ROLE';

    private ?ObjectManager $manager = null;

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->loadUser();
        $this->loadUserWithCartItem();
        $this->loadUserWithLargeCart();
        $this->loadUserWithOrder();
        $this->loadUserWithAdminRole();
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

    private function loadUserWithAdminRole(): void
    {
        $this->createUser(
            self::REFERENCE_USER_WITH_ADMIN_ROLE,
            'user-with-admin-role@mail.com',
            ['ROLE_ADMIN']
        );
    }

    private function createUser(string $reference, string $email, array $roles = []): void
    {
        $userRepository = $this->manager->getRepository(User::class);
        $userSignUpDto = new UserSignUpDto(
            Constant::NOTIFICATION_TYPE_EMAIL_ID,
            '123456',
            null,
            null,
            $email
        );
        /** @var User $user */
        $user = $userRepository->createUser($userSignUpDto);

        if ($roles) {
            $user->setRoles($roles);
        }

        $this->manager->persist($user);
        $this->manager->flush();

        $this->addReference($reference, $user);
    }
}
