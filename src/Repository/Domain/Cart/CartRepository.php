<?php

namespace App\Repository\Domain\Cart;

use App\Domain\Cart\Cart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cart>
 *
 * @method Cart|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cart|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cart[]    findAll()
 * @method Cart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cart::class);
    }

    /**
     * @return Cart|null
     */
    public function findLastCartNotSubmitted(): Cart|null
    {
        return $this->createQueryBuilder('c')
            ->where('c.submitted = :submitted')
            ->setParameter('submitted',false)
            ->orderBy('c.created_at','DESC')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findArticleNotPurchased(int $userId): Cart|null
    {
        return $this->createQueryBuilder('c')
                ->where('c.user = :user')
                ->setParameter(':user',$userId)
                ->andWhere('c.submitted = 0')
                ->getQuery()
                ->getSingleResult();
    }

//    public function findOneBySomeField($value): ?Cart
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
