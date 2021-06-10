<?php

namespace App\Repository;

use App\Entity\Bus;
use App\Entity\Trip;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Trip|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trip|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trip[]    findAll()
 * @method Trip[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TripRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trip::class);
    }

    /**
    * @return Trip[] Returns an array of Trip objects
    */

    public function findAllTripsWithBus4Route($route)
    {
        return $this->createQueryBuilder('t')
            ->Where('t.route = :val')
            ->setParameter('val', $route)
            ->innerJoin(Bus::class, 'b', Join::WITH, 'b.trip = t.id')
            ->AndWhere('b.trip is NOT NULL')
            ->orderBy('t.departureTime', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Trip
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
