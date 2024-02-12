<?php

namespace App\Repository;

use App\Entity\Pillbox;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pillbox>
 *
 * @method Pillbox|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pillbox|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pillbox[]    findAll()
 * @method Pillbox[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PillboxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pillbox::class);
    }

//    /**
//     * @return Pillbox[] Returns an array of Pillbox objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Pillbox
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
