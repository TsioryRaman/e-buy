<?php

namespace App\Domain\Article\repository;

use App\Domain\Article\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Article $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Article $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * Retourne les dernier biens
     *
     * @return QueryBuilder
     */
    public function findLatest():QueryBuilder
    {
        return $this->createQueryBuilder('a')
                    ->select('a')
                    ->leftJoin('a.articleViews','view','a.id = view.id')
                    ->leftJoin('a.like_by','u')
                    ->leftJoin('a.cartArticles','ca',Join::WITH,'a.id = ca.article')
                    ->leftJoin('ca.cart','c',Join::WITH,'ca.cart = c.id AND c.submitted = 0')
                    ->leftJoin('a.attachment','at')
                    ->addOrderBy('a.created_at',"DESC");
    }

    public function findLatestCrud():QueryBuilder
    {
        return $this->createQueryBuilder('a')
            ->select('a')
            ->leftJoin('a.attachment','at')
            ->addOrderBy('a.created_at',"DESC");
    }

    public function findArticleWithId(array $array_id):array
    {
        $query = $this->createQueryBuilder('a');
        foreach ($array_id as $key => $id)
        {
            $query->orWhere('a.id = :id' . $key)
                ->setParameter('id' . $key,$id);
        }

        return $query->getQuery()->getResult();
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
