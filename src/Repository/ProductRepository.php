<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * Permet d'obtenir 3 produits aléatoires dans la BDD
     */
    public function findAleasProducts()
    {
        $query = $this->createQueryBuilder('p')
            ->orderBy('RAND()')
            ->setMaxResults(3)
            ->getQuery();

        return $query->getResult();

    }

    /**
     * Permet d'obtenir un coup de coeur aléatoire dans la BDD
     */
    public function findOneAleaCrush()
    {
        $query = $this->createQueryBuilder('p')
            ->where('p.crush = true')
            ->orderBy('RAND()')
            ->setMaxResults(1)
            ->getQuery();

        return $query->getResult();

    }

    /**
     * Permet d'obtenir les 4 derniers produits dans la BDD
     */
    public function findlastProducts()
    {
        $query = $this->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->setMaxResults(4)
            ->getQuery();

        return $query->getResult();

    }


    public function findAllWithFilters($filters)
    {

        $qb = $this->createQueryBuilder('p');

        foreach($filters as $color) {
            $qb->orWhere('p.color_list LIKE :color_'.$color)->setParameter('color_'.$color, '%'.$color.'%');
        }

           // $colors = implode(", ", $filters);
           // $qb->where('p.color_list IN (:colors)')->setParameter('colors', $colors);


        return $qb->getQuery()->getResult();
    }

    public function findAllPerCategoryWithFilters($filters, $categoryId)
    {

        $qb = $this->createQueryBuilder('p');

       // $colors = implode(", ", $filters);

        foreach($filters as $color) {
            $qb->orWhere('p.color_list LIKE :color_'.$color)->setParameter('color_'.$color, '%'.$color.'%');
        }

        $qb->andWhere('p.category = :categoryId')
            ->setParameter('categoryId' , $categoryId);

        return $qb->getQuery()->getResult();
    }



}
