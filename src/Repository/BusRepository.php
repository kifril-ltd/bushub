<?php

namespace App\Repository;

use App\Entity\Bus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Bus|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bus|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bus[]    findAll()
 * @method Bus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bus::class);
    }

    /**
     * @return Bus[] Returns an array of Bus objects
     */
    public function findAllUnused(?int $tripId)
    {
        return $this->createQueryBuilder('b')
            ->Where('b.trip is NULL')
            ->orWhere('b.trip = :val')
            ->setParameter('val', $tripId)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Bus
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
