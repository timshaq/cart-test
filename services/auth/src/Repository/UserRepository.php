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

    public function createUser(UserSignUpDto $userSignUpDto): User
    {
        $login = $userSignUpDto->getPhone() ?? $userSignUpDto->getEmail();

        $userIsExist = (bool) $this->findOneBy(['login' => $login]);
        if ($userIsExist) {
            throw new ConflictHttpException('There is already such a user');
        }

        $user = new User();

        $user->setPromoId($userSignUpDto->getPromoId());
        $user->setLogin($userSignUpDto->getPhone() ?? $userSignUpDto->getEmail());
        $user->setPhone($userSignUpDto->getPhone());
        $user->setEmail($userSignUpDto->getEmail());

        $hashedPassword = $this->passwordHasher->hashPassword($user, $userSignUpDto->getPassword());
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
