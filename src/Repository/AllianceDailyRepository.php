<?php

namespace App\Repository;

use App\Entity\AllianceDaily;
use Doctrine\Persistence\ManagerRegistry;

class AllianceDailyRepository extends BaseRepository {

  public function __construct (ManagerRegistry $registry)  {
    parent::__construct($registry, AllianceDaily::class);
  }

  protected function getRelationshipAlias() : string {
    return "alliance";
  }
}