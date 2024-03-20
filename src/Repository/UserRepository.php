<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use function get_class;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }


    // Liste toutes les plantations
    public function p_byuser($user, $year)
    {
        return $this->createQueryBuilder('u')
            ->innerJoin('u.plants','p')
            ->innerJoin('p.consommable', 'c')
            ->where( 'p.user = :user' )
            ->andwhere('YEAR(p.createdat)= :year')
            ->setParameter('user', $user)
            ->setParameter('year', $year)
            ->select( 'SUM(p.quantite) as plante', '(p.state) as state', '(c.nom) as consonom', '(c.id) as idconso', '(c.icon_lien) as lien', '(c.badge1) as badge1', '(c.badge2) as badge2', '(c.badge3) as badge3')
            ->groupBy('p.consommable', 'p.state')
            ->orderBy('c.nom', 'ASC')
            ->orderBy('c.treeLeft', 'ASC')
            ->orderBy('p.state', 'ASC')
            ->getQuery()
            ->getResult();
    }


    // Liste toutes les récoltes
    public function r_byuser($user, $year)
    {
        $query = $this->createQueryBuilder('u')
            ->innerJoin('u.recoltes', 'r')
            ->innerJoin('r.consommable', 'c')
            ->where( 'r.user = :user' )
            ->andwhere('YEAR(r.createdat)= :year');

        $query->setParameter('user', $user)
            ->setParameter('year', $year)
            ->select('SUM(r.quantity) as recolte', '(c.nom) as consonom', '(c.id) as idconso', '(c.icon_lien) as lien', '(c.prix) as prix')
            ->groupBy('r.consommable')
            ->orderBy('c.nom', 'ASC')
            ->orderBy('c.treeLeft', 'ASC');

            return  $query->getQuery()->getResult();
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     * @throws ORMException
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }
        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

}
