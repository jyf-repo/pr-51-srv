<?php

namespace App\Repository;

use App\Entity\SearchProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SearchProduct>
 *
 * @method SearchProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method SearchProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method SearchProduct[]    findAll()
 * @method SearchProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SearchProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SearchProduct::class);
    }

//    /**
//     * @return SearchProduct[] Returns an array of SearchProduct objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SearchProduct
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
