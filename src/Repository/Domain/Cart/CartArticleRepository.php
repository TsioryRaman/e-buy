<?php

namespace App\Repository\Domain\Cart;

use App\Domain\Cart\CartArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CartArticle>
 *
 * @method CartArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method CartArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method CartArticle[]    findAll()
 * @method CartArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CartArticle::class);
    }

//    /**
//     * @return CartArticle[] Returns an array of CartArticle objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CartArticle
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function findCartList()
    {
        return $this->createQueryBuilder('ca')
            ->select('ca, c, a')
            ->leftJoin('ca.article','a',Join::WITH)
            ->leftJoin('ca.cart','c',Join::WITH,'c.id = ca.cart')
            ->where('c.submitted = :submitted')
            ->setParameter('submitted',false)
            ->orderBy('ca.quantity','DESC')
            ->getQuery()
            ->getResult();
    }
}
