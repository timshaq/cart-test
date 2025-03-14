<?php

namespace App\DataFixtures;

use App\Dto\UserSignUpDto;
use App\Entity\Constant;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const REFERENCE_USER = 'user';
    public function load(ObjectManager $manager): void
    {
        $userRepository = $manager->getRepository(User::class);
        $userSignUpDto = new UserSignUpDto(
            Constant::NOTIFICATION_TYPE_EMAIL_ID,
            '123456',
            null,
            null,
            'tester@mail.com'
        );
        $user = $userRepository->createUser($userSignUpDto);

        $manager->persist($user);
        $manager->flush();

        $this->addReference(self::REFERENCE_USER, $user);
    }
}
