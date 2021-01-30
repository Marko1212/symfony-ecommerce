<?php

namespace App\Repository;

use App\Entity\Review;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Review|null find($id, $lockMode = null, $lockVersion = null)
 * @method Review|null findOneBy(array $criteria, array $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    // /**
    //  * @return Review[] Returns an array of Review objects
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
    public function findOneBySomeField($value): ?Review
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    /**
     * Permet d'obtenir les 4 meilleurs produits dans la BDD
     */

    public function findBestProducts()
    {
        $query = $this->createQueryBuilder('r')
            ->select('avg(r.mark) as avg_mark, r.id, r.product, p.id, p.slug, p.description, p.price, p.url_image')
            ->join('r.product','p', 'WITH','r.product = p.id')
            ->groupBy('r.product')
            ->orderBy('AVG(r.mark)', 'DESC')
            ->setMaxResults(4)
            ->getQuery();

        return $query->getResult();
    }

}
