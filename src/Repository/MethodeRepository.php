<?php

namespace App\Repository;

use App\Entity\Methode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use function assert;

/**
 * @method Methode|null find($id, $lockMode = null, $lockVersion = null)
 * @method Methode|null findOneBy(array $criteria, array $orderBy = null)
 * @method Methode[]    findAll()
 * @method Methode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MethodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Methode::class);
    }


    /**
     * @return array<string, array<int, Methode>>
     */
    public function getMethodeListForChoices(): array
    {
        $data = [];
        foreach ($this->createMethodeListQueryBuilder()->getQuery()->getResult() as $method) {
            assert($method instanceof Methode);
            $data[$method->getNom()] = $method;
        }
        return $data;
    }

    public function createMethodeListQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('me')
            ->orderBy('me.nom', 'ASC');
        // ->addOrderBy('co.name', 'ASC');
    }

}
