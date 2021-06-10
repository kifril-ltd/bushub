<?php

namespace App\Repository;

use App\Entity\Route;
use App\Entity\Ticket;
use App\Entity\Trip;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ticket|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ticket|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ticket[]    findAll()
 * @method Ticket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ticket::class);
    }

    /**
     * @return Ticket[] Returns an array of Ticket objects
    */
    public function getTickets4Trip($trip,  $tripDate)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.trip = :trip')
            ->setParameter('trip', $trip)
            ->andWhere('t.tripDate = :tripDate')
            ->setParameter('tripDate', $tripDate)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getTripsIncome($startDate, $endDate)
    {
        return $this->createQueryBuilder('t')
            ->innerJoin(Trip::class, 'tr', Join::WITH, 't.trip = tr.id')
            ->innerJoin(Route::class, 'r', Join::WITH, 'tr.route = r.id')
            ->andWhere('t.tripDate BETWEEN :start_date AND :end_date')
            ->setParameter('start_date', $startDate)
            ->setParameter('end_date', $endDate)
            ->select("r.departurePoint, r.arrivalPoint, tr.departureTime, tr.arrivalTime, SUM(t.price) as income")
            ->groupBy('t.trip, r.departurePoint, r.arrivalPoint, tr.departureTime,  tr.arrivalTime')
            ->orderBy('income', 'DESC')
            ->getQuery()
            ->getArrayResult()
            ;
    }

    public function getCashiersIncome($startDate, $endDate)
    {
        return $this->createQueryBuilder('t')
            ->innerJoin(User::class, 'u', Join::WITH, 't.seller = u.id')
            ->andWhere('t.saleDatetime BETWEEN :start_date AND :end_date')
            ->setParameter('start_date', $startDate)
            ->setParameter('end_date', $endDate)
            ->select("u.firstName, u.lastName, u.patronymic, u.passportNumber, SUM(t.price) as income")
            ->groupBy('t.seller, u.firstName, u.lastName, u.patronymic, u.passportNumber')
            ->orderBy('income', 'DESC')
            ->getQuery()
            ->getArrayResult()
            ;
    }

    public function getRoutesIncome($startDate, $endDate)
    {
        return $this->createQueryBuilder('t')
            ->innerJoin(Trip::class, 'tr', Join::WITH, 't.trip = tr.id')
            ->innerJoin(Route::class, 'r', Join::WITH, 'tr.route = r.id')
            ->andWhere('t.tripDate BETWEEN :start_date AND :end_date')
            ->setParameter('start_date', $startDate)
            ->setParameter('end_date', $endDate)
            ->select("r.departurePoint, r.arrivalPoint, SUM(t.price) as income")
            ->groupBy('r.id, r.departurePoint, r.arrivalPoint')
            ->orderBy('income', 'DESC')
            ->getQuery()
            ->getArrayResult()
            ;
    }

    /*
    public function findOneBySomeField($value): ?Ticket
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
