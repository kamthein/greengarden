<?php

namespace App\Repository;

use App\Entity\Panier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Panier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Panier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Panier[]    findAll()
 * @method Panier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PanierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Panier::class);
    }

    //Liste des paniers (par utilisateur)
    public function panierbyuser($user)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.flux', 'f')
            ->innerJoin('f.user', 'u')
            ->where( 'f.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }


}
