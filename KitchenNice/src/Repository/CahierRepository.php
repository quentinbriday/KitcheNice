<?php

namespace App\Repository;

use App\Entity\Cahier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Cahier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cahier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cahier[]    findAll()
 * @method Cahier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CahierRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Cahier::class);
    }

    // /**
    //  * @return Cahier[] Returns an array of Cahier objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Cahier
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
