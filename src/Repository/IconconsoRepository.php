<?php

namespace App\Repository;

use App\Entity\Iconconso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Iconconso|null find($id, $lockMode = null, $lockVersion = null)
 * @method Iconconso|null findOneBy(array $criteria, array $orderBy = null)
 * @method Iconconso[]    findAll()
 * @method Iconconso[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IconconsoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Iconconso::class);
    }

}
