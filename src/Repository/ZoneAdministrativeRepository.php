<?php

namespace App\Repository;

use App\Entity\ZoneAdministrative;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use function assert;

/**
 * @method ZoneAdministrative|null find($id, $lockMode = null, $lockVersion = null)
 * @method ZoneAdministrative|null findOneBy(array $criteria, array $orderBy = null)
 * @method ZoneAdministrative[]    findAll()
 * @method ZoneAdministrative[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ZoneAdministrativeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ZoneAdministrative::class);
    }

    /**
     * @return array<string, array<int, ZoneAdministrative>>
     */
    public function getZonesListForChoices(): array
    {
        $data = [];
        foreach ($this->createZonesListQueryBuilder()->getQuery()->getResult() as $zone) {
            assert($zone instanceof ZoneAdministrative);
            $data[$zone->getCountryCode()][] = $zone;
        }

        return $data;
    }

    public function createZonesListQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('za')
            ->orderBy('za.countryCode', 'ASC')
            ->addOrderBy('za.name', 'ASC');
    }

}
