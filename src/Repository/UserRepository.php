<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the User's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }

    /**
     * Return users collection.
     *
     * @param integer $id
     * @return array
     */
    public function getUsersForCustomer(int $id): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.customer = :val')
            ->setParameter('val', $id)
            ->andWhere('u.roles LIKE :role')
            ->setParameter('role', '%ROLE_USER%')
            ->getQuery()
            ->getResult();
    }

    /**
     * Return user.
     *
     * @param integer $customerId
     * @param integer $userId
     * @return array
     */
    public function getUserForCustomer(int $customerId, int $userId): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.id = :id')
            ->setParameter('id', $userId)
            ->andWhere('u.customer = :val')
            ->setParameter('val', $customerId)
            ->andWhere('u.roles LIKE :role')
            ->setParameter('role', '%ROLE_USER%')
            ->getQuery()
            ->getResult();
    }
}
