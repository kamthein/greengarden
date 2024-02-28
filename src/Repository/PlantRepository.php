<?php

namespace App\Repository;

use App\Entity\Plant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Plant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Plant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Plant[]    findAll()
 * @method Plant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Plant::class);
    }

    //utilisateur avec le plus de plantation
    public function user_plant()
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.user', 'u')
            ->select('sum(p.quantite) as nombre, u.nickname as nickname')
            ->groupBy('nickname')
            ->orderBy('nombre', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }
}
