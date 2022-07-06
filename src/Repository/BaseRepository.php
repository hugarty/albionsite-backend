<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class BaseRepository extends ServiceEntityRepository {

  public function getFromIds($ids, $from, $to)
  {
    $relationshipAlias = $this->getRelationshipAlias();
    return $this->createQueryBuilder("entity")
        ->addSelect("$relationshipAlias.id", "$relationshipAlias.name")
        ->leftJoin("entity.$relationshipAlias", $relationshipAlias)
        ->andWhere("entity.date BETWEEN :from AND :to")
        ->andWhere("$relationshipAlias.id IN (:ids)")
        ->addOrderBy("entity.date", 'ASC')
        ->addOrderBy("$relationshipAlias.name", 'ASC')
        ->addOrderBy("$relationshipAlias.id", 'ASC')
        ->setParameter('from', $from)
        ->setParameter('to', $to)
        ->setParameter('ids', $ids)
        ->getQuery()
        ->getResult();
  }

  abstract protected function getRelationshipAlias() : string;

}