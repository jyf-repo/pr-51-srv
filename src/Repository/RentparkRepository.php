<?php

namespace App\Repository;

use App\Entity\Rentpark;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rentpark>
 *
 * @method Rentpark|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rentpark|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rentpark[]    findAll()
 * @method Rentpark[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RentparkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rentpark::class);
    }

//    /**
//     * @return Rentpark[] Returns an array of Rentpark objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Rentpark
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
