<?php

namespace App\Repository;

use App\Entity\Recolte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineExtensions\Query\Mysql;
use Symfony\Component\Security\Core\User\UserInterface;
use function Symfony\Component\Translation\t;

/**
 * @method Recolte|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recolte|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recolte[]    findAll()
 * @method Recolte[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecolteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recolte::class);
    }

     /**
      * @return Recolte[] Returns an array of Recolte objects
      */

    // Requete pour le graphique dans STAT, par utilisateur
    public function statbyuser($user, $year)
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.consommable', 'c')
            ->where( 'r.user = :user')
            ->andwhere('YEAR(r.createdat)= :year')
            ->setParameter('user', $user)
            ->setParameter('year', $year)
            ->select('MONTH(r.createdat) as mois', 'YEAR(r.createdat) as annee', 'SUM(r.quantity) as quantity_tot', 'COUNT(c.nom) as nbr_variete')
            ->groupBy('annee', 'mois')
            ->getQuery()
            ->getResult();
    }


    // Requete pour le graphique par METHODE dans STAT
    public function QteBymethodbyuser($user, $year)
    {
        return $this->createQueryBuilder('r')
           // ->innerJoin('r.consommable', 'c')
            ->innerJoin('r.methode', 'm')
            ->where( 'r.user = :user')
            ->andwhere('YEAR(r.createdat)= :year')
            ->setParameter('user', $user)
            ->setParameter('year', $year)
            ->select('SUM(r.quantity) as quantity_tot', 'm.nom as methode')
            ->groupBy('methode')
            ->getQuery()
            ->getResult();
    }

    // calcul du nombre de calories récoltées
    public function Cal_byuser($user, $year)
    {
        $query = $this->createQueryBuilder('r')
            ->innerJoin('r.user', 'u')
            ->innerJoin('r.consommable', 'c')
            ->where( 'r.user = :user')
            ->andwhere('YEAR(r.createdat)= :year');

            $query->setParameter('user', $user)
                ->setParameter('year', $year)
            ->select('u.nickname as nickname, sum(r.quantity * c.calorie) as nombre');

              return  $query->getQuery()->getResult();
    }
}
