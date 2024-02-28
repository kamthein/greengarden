<?php

namespace App\Repository;

use App\Entity\Flux;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Flux|null find($id, $lockMode = null, $lockVersion = null)
 * @method Flux|null findOneBy(array $criteria, array $orderBy = null)
 * @method Flux[]    findAll()
 * @method Flux[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FluxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Flux::class);
    }

    //Liste de tous les flux
    public function flux_tous()
    {
        return $this->createQueryBuilder('f')
            ->where('f.shared = :shared')
            ->setParameter('shared', true)
            ->orderBy('f.updatedat', 'DESC')
            ->getQuery()
            ->getResult();
    }

    //Liste de tous les flux avec photo
    public function flux_photo_panier()
    {
        return $this->createQueryBuilder('f')
            ->innerJoin('f.panier', 'pa')
            ->innerJoin('pa.photo', 'p')
            ->where('f.shared = :shared')
            ->andWhere( 'p IS NOT NULL ')
            ->andWhere( 'p.imageName <> :default_recolte ')
            ->andWhere( 'p.imageName <> :default_plantation ')
            ->setParameter('default_recolte', "recoltes.png")
            ->setParameter('default_plantation', "plantation.png")
            ->setParameter('shared', true)
            ->orderBy('f.updatedat', 'DESC')
            ->getQuery()
            ->getResult();
    }

    //Liste de tous les flux avec photo
    public function flux_photo_post()
    {
        return $this->createQueryBuilder('f')
            ->innerJoin('f.post', 'po')
            ->innerJoin('po.photos', 'p')
            ->where('f.shared = :shared')
            ->setParameter('shared', true)
            ->orderBy('f.updatedat', 'DESC')
            ->getQuery()
            ->getResult();
    }


    //Liste des posts (par utilisateur)
    public function postbyuser($user)
    {
        return $this->createQueryBuilder('f')
            ->innerJoin('f.post', 'p')
            ->innerJoin('f.user', 'u')
            ->where( 'f.user = :user')
            ->setParameter('user', $user)
            ->orderBy('p.createdat', 'DESC')
            ->getQuery()
            ->getResult();
    }

    //Liste des flux du jour (par utilisateur)
    public function fluxbyuserToday($user, $date)
    {
        return $this->createQueryBuilder('f')
            ->innerJoin('f.user', 'u')
            ->leftJoin('f.panier', 'pa')
            ->leftJoin('pa.plants', 'pl')
            ->leftJoin('pa.recoltes', 're')
            ->where( 'f.user = :user')
            ->andWhere('(f.updatedat BETWEEN :dateFrom AND :dateTo) OR (pl.createdat BETWEEN :dateFrom AND :dateTo) OR (re.createdat BETWEEN :dateFrom AND :dateTo) ')
            ->setParameters([
                'dateFrom' => $date->format('Y-m-d'),
                'dateTo' => (clone $date)->modify('+ 1 day'),
                'user'=> $user
            ])
            ->orderBy('f.updatedat', 'DESC')
            ->getQuery()
            ->getResult();
    }

    //Liste des dates des plantations dans le mois(par utilisateur)
    public function dateFluxPlant($user, $dateDbt, $dateFin)
    {
        return $this->createQueryBuilder('f')
            ->innerJoin('f.user', 'u')
            ->leftJoin('f.panier', 'pa')
            ->leftJoin('pa.plants', 'pl')
            ->where( 'f.user = :user')
            ->andWhere('pl.createdat BETWEEN :dateFrom AND :dateTo')
            ->setParameters([
                'dateFrom' => $dateDbt->format('Y-m-d'),
                'dateTo' => $dateFin->format('Y-m-d'),
                'user'=> $user
            ])
            ->select('pl.createdat as date_event', '0 as recolte', 'pa.id as plant', '0 as post', '0 as achat')
            ->groupBy('date_event')
            ->orderBy('pl.createdat', 'DESC')
            ->getQuery()
            ->getResult();
    }

        //Liste des dates des flux dans le mois(par utilisateur)
        public function dateFluxRecolte($user, $dateDbt, $dateFin)
        {
            return $this->createQueryBuilder('f')
                ->innerJoin('f.user', 'u')
                ->leftJoin('f.panier', 'pa')
                ->leftJoin('pa.recoltes', 're')
                ->where( 'f.user = :user')
                ->andWhere('re.createdat BETWEEN :dateFrom AND :dateTo')
                ->setParameters([
                    'dateFrom' => $dateDbt->format('Y-m-d'),
                    'dateTo' => $dateFin->format('Y-m-d'),
                    'user'=> $user
                ])
                ->select('re.createdat as date_event', 're.id as recolte', '0 as plant', '0 as post', '0 as achat')
                ->groupBy('date_event')
                ->orderBy('re.createdat', 'DESC')
                ->getQuery()
                ->getResult();
        }

        //Liste des dates des flux dans le mois(par utilisateur)
        public function dateFluxNote($user, $dateDbt, $dateFin)
        {
            return $this->createQueryBuilder('f')
                ->innerJoin('f.user', 'u')
                ->innerJoin('f.post', 'po')
                ->where( 'f.user = :user')
                ->andWhere('f.createdat BETWEEN :dateFrom AND :dateTo')
                ->setParameters([
                    'dateFrom' => $dateDbt->format('Y-m-d'),
                    'dateTo' => $dateFin->format('Y-m-d'),
                    'user'=> $user
                ])
                ->select('f.createdat as date_event', '0 as recolte', '0 as plant', 'po.id as post', '0 as achat')
                ->groupBy('date_event')
                ->orderBy('f.createdat', 'DESC')
                ->getQuery()
                ->getResult();
        }

    //Liste des flux des amis suivis
    public function flux_amis($users)
    {
        return $this->createQueryBuilder('f')
            ->where( 'f.user IN (:users)')
            ->andWhere('f.shared = :shared')
            ->setParameters([
                'users' => $users,
                'shared' => true,
            ])
            ->orderBy('f.createdat', 'DESC')
            ->getQuery()
            ->getResult();
    }


// Trouver les flux vides sans panier (ous avec panier sans récolte, plant), sans achat, sans post.
    public function TrouverVide()
    {
        return $this->createQueryBuilder('f')
            ->leftJoin('f.post', 'po')
            ->leftJoin('f.panier', 'p')
            ->leftJoin('p.recoltes', 'r')
            ->leftJoin('p.plants', 'pl')
            ->where('po IS NULL')
            ->andwhere('r IS NULL')
            ->andwhere('pl IS NULL')
            ->orderBy('f.id', 'ASC')
            ->getQuery()
            ->getResult();
    }


}
