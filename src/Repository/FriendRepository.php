<?php

namespace App\Repository;

use App\Entity\Friend;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Friend|null find($id, $lockMode = null, $lockVersion = null)
 * @method Friend|null findOneBy(array $criteria, array $orderBy = null)
 * @method Friend[]    findAll()
 * @method Friend[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FriendRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Friend::class);
    }

    public function findfriendBy($user)
    {
        return $this->createQueryBuilder('f')
            ->innerJoin('f.user_followed', 'u')
            ->where( 'f.user_friend = :user')
            ->setParameter('user', $user)
            ->orderBy('u.nickname', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findfriendfollowedBy($user)
    {
        return $this->createQueryBuilder('f')
            ->innerJoin('f.user_followed', 'u')
            ->where( 'f.user_friend = :user')
            ->setParameter('user', $user)
            ->select('u.id')
            ->orderBy('u.nickname', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function isfriend($user_co, $user)
    {
        return $this->createQueryBuilder('f')
            ->innerJoin('f.user_followed', 'u')
            ->where( 'f.user_friend = :user_co')
            ->andwhere( 'f.user_followed = :user')
            ->setParameters([
                'user' => $user,
                'user_co' => $user_co
            ])
            ->select('f.id')
            ->getQuery()
            ->getResult()
            ;
    }

}
