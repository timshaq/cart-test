<?php

namespace App\Repository;

use App\Dto\UserSignUpDto;
use App\Entity\Constant;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        ManagerRegistry $registry
    )
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @throws ORMException
     */
    public function createUser(UserSignUpDto $userSignUpDto): User
    {
        $login = $userSignUpDto->phone ?? $userSignUpDto->email;

        $userIsExist = (bool) $this->findOneBy(['login' => $login]);
        if ($userIsExist) {
            throw new ConflictHttpException('There is already such a user');
        }

        $user = new User();

        $user->setPromoId($userSignUpDto->promoId);
        $user->setLogin($userSignUpDto->phone ?? $userSignUpDto->email);
        $user->setPhone($userSignUpDto->phone);
        $user->setEmail($userSignUpDto->email);
        $user->setNotificationType($this->getEntityManager()->getReference(
            Constant::class,
            $userSignUpDto->notificationTypeId
        ));

        $hashedPassword = $this->passwordHasher->hashPassword($user, $userSignUpDto->password);
        $user->setPassword($hashedPassword);

        return $user;
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }
}
