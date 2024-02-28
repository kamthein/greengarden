<?php

namespace App\Repository;

use App\Entity\Achat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Achat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Achat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Achat[]    findAll()
 * @method Achat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AchatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Achat::class);
    }


    //Liste des achats du jour (par utilisateur)
    public function achatbyuserToday($user, $date)
    {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.user', 'u')
            ->where( 'a.user = :user')
            ->andWhere('a.createdat BETWEEN :dateFrom AND :dateTo')
            ->setParameters([
                'dateFrom' => $date->format('Y-m-d'),
                'dateTo' => (clone $date)->modify('+ 1 day'),
                'user'=> $user
            ])
            ->orderBy('a.createdat', 'DESC')
            ->getQuery()
            ->getResult();
    }


    //Liste des dates des flux dans le mois(par utilisateur)
    public function dateAchat($user, $dateDbt, $dateFin)
    {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.user', 'u')
            ->where( 'a.user = :user')
            ->andWhere('a.createdat BETWEEN :dateFrom AND :dateTo')
            ->setParameters([
                'dateFrom' => $dateDbt->format('Y-m-d'),
                'dateTo' => $dateFin->format('Y-m-d'),
                'user'=> $user
            ])
            ->select('a.createdat as date_event', '0 as recolte', '0 as plant', '0 as traite', '0 as post', 'a.id as achat')
            ->groupBy('date_event')
            ->orderBy('a.createdat', 'DESC')
            ->getQuery()
            ->getResult();
    }

}
