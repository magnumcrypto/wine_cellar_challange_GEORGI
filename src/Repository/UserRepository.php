<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $user, bool $flush = false): void
    {
        $this->getEntityManager()->persist($user);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
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

    public function registerUser(object $dataUser, UserPasswordHasherInterface $passwordHasher): ?string
    {
        if (!isset($dataUser) || empty($dataUser) || !isset($dataUser->email) || empty($dataUser->email)) {
            return null;
        }
        try {
            $user = new User();
            $user->setName($dataUser->name);
            $user->setSurnames($dataUser->surnames);
            $user->setEmail(strtolower($dataUser->email));

            //hash the password
            $hashdedPassword = $passwordHasher->hashPassword($user, $dataUser->password);
            $user->setPassword($hashdedPassword);
            $this->save($user, true);
            return $user->getEmail();
        } catch (\Exception $e) {
            return null;
        }
    }
}
