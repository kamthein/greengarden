<?php

namespace App\Repository;

use App\Entity\Taille;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use function assert;

/**
 * @method Taille|null find($id, $lockMode = null, $lockVersion = null)
 * @method Taille|null findOneBy(array $criteria, array $orderBy = null)
 * @method Taille[]    findAll()
 * @method Taille[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TailleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Taille::class);
    }

    /**
     * @return array<string, array<int, Taille>>
     */
    public function getTailleListForChoices(): array
    {
        $data = [];
        foreach ($this->createTailleListQueryBuilder()->getQuery()->getResult() as $taille) {
            assert($taille instanceof Taille);
            $data[$taille->getMetre()] = $taille;
        }

        return $data;
    }

    public function createTailleListQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('ta');
            //->orderBy('ta.nom', 'ASC');
    }

}
