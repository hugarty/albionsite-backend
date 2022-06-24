<?php

namespace App\Repository;

use App\Entity\AllianceDaily;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AllianceDailyRepository extends ServiceEntityRepository {

  public function __construct (ManagerRegistry $registry)  {
    parent::__construct($registry, AllianceDaily::class);
  }

  public function findBetwteenDates(\Datetime $startDate, \Datetime $endDate)
  {
      $from = new \DateTime($startDate->format("Y-m-d")." 00:00:00");
      $to   = new \DateTime($endDate->format("Y-m-d")." 23:59:59");
  
      $qb = $this->createQueryBuilder("e");
      $qb
          ->andWhere('e.date BETWEEN :from AND :to')
          ->setParameter('from', $from )
          ->setParameter('to', $to)
      ;
      return $qb->getQuery()->getResult();
  }

}