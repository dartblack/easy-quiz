<?php

namespace App\Repository;

use App\Entity\QuizSession;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ParameterType;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuizSession>
 *
 * @method QuizSession|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuizSession|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuizSession[]    findAll()
 * @method QuizSession[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizSessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuizSession::class);
    }

    public function add(QuizSession $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(QuizSession $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getTopMembers(int $limit = 10)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.finished = :finished')
            ->setParameter('finished', true, ParameterType::BOOLEAN)
            ->andWhere('q.bestResult = :bestResult')
            ->setParameter('bestResult', true, ParameterType::BOOLEAN)
            ->addOrderBy('q.score', 'DESC')
            ->addOrderBy('q.quizTime', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getExpiredSessions()
    {
        $expiredDate = new \DateTimeImmutable('-300 seconds');
        return $this->createQueryBuilder('q')
            ->andWhere('q.finished = :finished')
            ->setParameter('finished', false, ParameterType::BOOLEAN)
            ->andWhere('q.startAt < :startAt')
            ->setParameter('startAt', $expiredDate)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return QuizSession[] Returns an array of QuizSession objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('q.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?QuizSession
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
