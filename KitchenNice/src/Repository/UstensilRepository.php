<?php

namespace App\Repository;

use App\Entity\Ustensil;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Ustensil|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ustensil|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ustensil[]    findAll()
 * @method Ustensil[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UstensilRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Ustensil::class);
    }

    // /**
    //  * @return Ustensil[] Returns an array of Ustensil objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Ustensil
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
