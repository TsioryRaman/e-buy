<?php

namespace App\Repository\Domain\Article;

use App\Domain\Article\ArticleView;
use App\Domain\Auth\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ArticleView>
 *
 * @method ArticleView|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticleView|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticleView[]    findAll()
 * @method ArticleView[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleViewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArticleView::class);
    }

    public function findIfUserViewArticle(int $articleId,?User $user): ArticleView|null
    {
        $query =  $this->createQueryBuilder('av')
            ->select('av')
            ->where('av.article = :articleId')
            ->setParameter('articleId',$articleId);
        if($user !== null)
        {
            $query
                ->andWhere('av.view_by = :userId')
                ->setParameter('userId',$user->getId());
        }else{
            $query->andWhere('av.view_by is NULL');
        }
        return $query
                    ->getQuery()
                    ->getOneOrNullResult();
    }

//    /**
//     * @return ArticleView[] Returns an array of ArticleView objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ArticleView
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
