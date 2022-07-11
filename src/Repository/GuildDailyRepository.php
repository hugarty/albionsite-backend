<?php

namespace App\Repository;

use App\Entity\GuildDaily;
use DateTime;
use DateTimeZone;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GuildDailyRepository extends BaseRepository {

  public function __construct (ManagerRegistry $registry)  {
    parent::__construct($registry, GuildDaily::class);
  }

  protected function getRelationshipAlias() : string {
    return "guild";
  }

  public function getFiveMostFamousGuildDaily()
  {
    $lastDate = $this->getLastDate();
    $guilds = $this->getFiveMostFamousGuilds($lastDate);
    
    $guildsIds = array_map(function ($item) {
      return $item->guild->id;
    }, $guilds);
    
    $today = new DateTime('today', new DateTimeZone('America/Sao_Paulo'));
    $todayMinusSevenDays = new DateTime('today', new DateTimeZone('America/Sao_Paulo'));
    $todayMinusSevenDays->modify("-7 day");
    return $this->getFromIds($guildsIds, $todayMinusSevenDays, $today);
  }

  private function getFiveMostFamousGuilds($today) {
    return $this->createQueryBuilder("gd")
    ->addOrderBy('gd.fame', 'DESC')
    ->andWhere('gd.date = :date')
    ->setParameter('date', $today)
    ->setMaxResults(5)
    ->getQuery()
    ->getResult();
  }

  private function getLastDate() {
    $result = $this->createQueryBuilder("gd")
    ->addOrderBy('gd.date', 'DESC')
    ->setMaxResults(1)
    ->getQuery()
    ->getResult();

    if ($result){
      return $result[0]->date;
    }
    throw new NotFoundHttpException("not found");
  }
}