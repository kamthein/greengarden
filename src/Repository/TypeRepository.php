<?php

namespace App\Repository;

use App\Entity\Type;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Type|null find($id, $lockMode = null, $lockVersion = null)
 * @method Type|null findOneBy(array $criteria, array $orderBy = null)
 * @method Type[]    findAll()
 * @method Type[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Type::class);
    }


    // /**
    //  * @return Type[] Returns an array of Type objects, POUR afficher seulement les type concernant les achat
    //  */
    public function getTypeListForAchat(){
        $data = [];
        foreach ($this->createTypeAchatListQueryBuilder()->getQuery()->getResult() as $type) {
            assert($type instanceof Type);
            $data[$type->getNom()] = $type;
        }

        return $data;
    }

    public function createTypeAchatListQueryBuilder(): QueryBuilder
    {
        $qb = $this->createQueryBuilder('ty');

        return $qb
            //->select('*')
            //->andWhere('ty.achat = true')
            //->setParameter('Level', 1)
            ->orderBy('ty.nom', 'ASC');
    }


}
