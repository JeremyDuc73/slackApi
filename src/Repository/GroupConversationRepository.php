<?php

namespace App\Repository;

use App\Entity\GroupConversation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GroupConversation>
 *
 * @method GroupConversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupConversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupConversation[]    findAll()
 * @method GroupConversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupConversationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroupConversation::class);
    }

//    /**
//     * @return GroupConversation[] Returns an array of GroupConversation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?GroupConversation
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
