<?php

namespace App\Repository;

use App\Entity\Rentcategories;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rentcategories>
 *
 * @method Rentcategories|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rentcategories|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rentcategories[]    findAll()
 * @method Rentcategories[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RentcategoriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rentcategories::class);
    }

//    /**
//     * @return Rentcategories[] Returns an array of Rentcategories objects
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

//    public function findOneBySomeField($value): ?Rentcategories
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
