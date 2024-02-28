<?php

namespace App\Repository;

use App\Entity\State;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use function assert;

/**
 * @extends ServiceEntityRepository<State>
 *
 * @state State|null find($id, $lockMode = null, $lockVersion = null)
 * @state State|null findOneBy(array $criteria, array $orderBy = null)
 * @state State[]    findAll()
 * @state State[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, State::class);
    }



    /**
     * @return array<string, array<int, State>>
     */
    public function getStateListForChoices(): array
    {
        $data = [];
        foreach ($this->createStateListQueryBuilder()->getQuery()->getResult() as $state) {
            assert($state instanceof State);
            $data[$state->getNom()] = $state;
        }
        return $data;
    }

    public function createStateListQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('me')
            ->orderBy('me.nom', 'ASC');
        // ->addOrderBy('co.name', 'ASC');
    }



   
//    /**
//     * @return State[] Returns an array of State objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?State
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
