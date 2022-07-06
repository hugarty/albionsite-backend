<?php

namespace App\Repository;

use App\Entity\AllianceWeekly;
use Doctrine\Persistence\ManagerRegistry;

class AllianceWeeklyRepository extends BaseRepository {

  public function __construct (ManagerRegistry $registry)  {
    parent::__construct($registry, AllianceWeekly::class);
  }

  protected function getRelationshipAlias() : string {
    return "alliance";
  }
}