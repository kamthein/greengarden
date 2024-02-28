<?php

namespace App\Repository;

use App\Entity\Consommable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use function assert;

/**
 * @method Consommable|null find($id, $lockMode = null, $lockVersion = null)
 * @method Consommable|null findOneBy(array $criteria, array $orderBy = null)
 * @method Consommable[]    findAll()
 * @method Consommable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConsommableRepository extends NestedTreeRepository implements ServiceEntityRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        $manager = $registry->getManagerForClass(Consommable::class);
        assert($manager instanceof EntityManagerInterface);

        parent::__construct($manager, $manager->getClassMetadata(Consommable::class));
    }

    public function findallbynomasc()
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.icone', 'i')
            ->select( 'c.nom as nom', 'i.lien as icone' )
            ->orderBy('c.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }
    /**
     * @return array<string, array<int, Consommable>>
     */
    public function getConsoListForChoices(): array
    {
        $data = [];
        foreach ($this->createConsoListQueryBuilder()->getQuery()->getResult() as $conso) {
            assert($conso instanceof Consommable);
            $data[$conso->getNom()] = $conso;
        }

        return $data;
    }

    public function createConsoListQueryBuilder(): QueryBuilder
    {
        $qb = $this->createQueryBuilder('co');

        return $qb
            ->andWhere($qb->expr()->gt('co.treeLevel', ':minLevel'))
            ->orderBy('co.nom', 'ASC')
            ->orderBy('co.treeLeft', 'ASC')
            ->setParameter('minLevel', 1);
    }


}
