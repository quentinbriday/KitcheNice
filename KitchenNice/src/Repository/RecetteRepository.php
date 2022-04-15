<?php

namespace App\Repository;

use App\Entity\Recette;
use App\Entity\RecetteSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Recette|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recette|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recette[]    findAll()
 * @method Recette[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecetteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Recette::class);
    }

    /**
     * @param RecetteSearch $search
     * @return Query
     */
    public function findAllRecentQuery(RecetteSearch $search): Query
    {
        $query = $this->createQueryBuilder('r')
            ->andWhere('r.is_private = 0')
            ->orderBy('r.date_creation');

        if ($search->getDifficulte()) {
            $query->andWhere('r.difficulte = :dif')
                ->setParameter('dif', $search->getDifficulte());
        }
        if ($search->getCout())
        {
            $query->andWhere('r.cout = :cout')
                ->setParameter('cout', $search->getCout());
        }
        if ($search->getTitre())
        {
            $query->andWhere('r.titre LIKE :titre')
                ->setParameter('titre', '%'.$search->getTitre().'%');
        }
        if (!empty($search->getTypes()))
        {
            $query->join('r.types', 't');
            $i = 0;
            $types = $search->getTypes();
            $query->andWhere('t.type = :type')
                ->setParameter('type', $types->first()->getType());
            $types->remove(0);
            foreach ($types as $type)
            {
                $query->orWhere('t.type = :type'.$i)
                    ->setParameter('type'.$i, $type->getType());
                $i += 1;
            }
        }

        if (!empty($search->getIngredients()))
        {
            $i = 0;
            $ingredients = $search->getIngredients();
            /*
            $query->andWhere('i.nom = :nom')
                ->setParameter('nom', $ingredients->first()->getNom());
            $ingredients->remove(0);*/
            foreach ($ingredients as $ingredient)
            {
                $query->join('r.quantites', 'q'.$i);
                $query->join('q'.$i.'.ingredient', 'i'.$i);
                $query->andWhere('i'.$i.'.nom = :nom'.$i)
                    ->setParameter('nom'.$i, $ingredient->getNom());
                $i += 1;
            }
            /*
            $query
                ->andWhere('r.types IN :types')
                ->setParameter('types', '('.$search->typesToString().')');*/
        }


        return $query->getQuery();
    }

    /**
     * @param $id
     * @param $user
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOnePublic($id)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.id = :id')
            ->setParameter('id', $id)
            ->andWhere('r.is_private = 0')
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /**
     * @param $user
     * @param $id
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByUserAndId($user, $id)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.id = :id')
            ->setParameter('id', $id)
            ->andWhere('r.membre = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    // /**
    //  * @return Recette[] Returns an array of Recette objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Recette
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
