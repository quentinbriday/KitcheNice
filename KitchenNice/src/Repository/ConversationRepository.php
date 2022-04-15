<?php

namespace App\Repository;

use App\Entity\Conversation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Conversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conversation[]    findAll()
 * @method Conversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Conversation::class);
    }

    public function findByUser($user)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.membre1 = :val')
            ->orWhere('c.membre2 = :val')
            ->setParameter('val', $user)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param $user1
     * @param $user2
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByUsers($user1, $user2)
    {
        return $this->createQueryBuilder('c')
            ->where('c.membre1 = :val AND c.membre2 = :val2')
            ->orWhere('c.membre2 = :val AND c.membre1 = :val2')
            ->setParameter('val', $user1)
            ->setParameter('val2', $user2)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneById($id)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    // /**
    //  * @return Conversation[] Returns an array of Conversation objects
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
    public function findOneBySomeField($value): ?Conversation
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
