<?php

namespace App\Repository;

use App\Entity\Friends;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr;

/**
 * @extends ServiceEntityRepository<Friends>
 *
 * @method Friends|null find($id, $lockMode = null, $lockVersion = null)
 * @method Friends|null findOneBy(array $criteria, array $orderBy = null)
 * @method Friends[]    findAll()
 * @method Friends[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FriendsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Friends::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Friends $entity, bool $flush = false): void
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
    public function remove(Friends $entity, bool $flush = false): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

   /**
    * @return Friends[] Returns an array of Friends objects
    */
   public function findFriends($value): array
   {
       return $this->createQueryBuilder('f')

             ->Where('f.verified = :true')
             ->setParameter('true', true)

            ->andWhere(
                new Expr\Orx([
                    'f.avatar_ID1 = :val',
                    'f.avatar_ID2 = :val',
                ])
            )
           ->setParameter('val', $value)


           ->getQuery()
           ->getResult()
       ;
   }

   /**
    * @return Friends[] Returns an array of Friends objects
    */
   public function findFriendsUser($value): array
   {
       return $this->createQueryBuilder('f')

            ->Where(
                new Expr\Orx([
                    'f.avatar_ID1 = :val',
                    'f.avatar_ID2 = :val',
                ])
            )
           ->setParameter('val', $value)


           ->getQuery()
           ->getResult()
       ;
   }
   /**
    * @return Friends[] Returns an array of Friends objects
    */
    public function findAllFriends(): array
    {
        return $this->createQueryBuilder('f')
 
              ->Where('f.verified = :true')
              ->setParameter('true', true)
 
            ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?Friends
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
