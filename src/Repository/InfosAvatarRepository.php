<?php

namespace App\Repository;

use App\Entity\InfosAvatar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InfosAvatar>
 *
 * @method InfosAvatar|null find($id, $lockMode = null, $lockVersion = null)
 * @method InfosAvatar|null findOneBy(array $criteria, array $orderBy = null)
 * @method InfosAvatar[]    findAll()
 * @method InfosAvatar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InfosAvatarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InfosAvatar::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(InfosAvatar $entity, bool $flush = false): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(InfosAvatar $entity, bool $flush = false): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

//    /**
//     * @return InfosAvatar[] Returns an array of InfosAvatar objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?InfosAvatar
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
