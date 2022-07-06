<?php

namespace App\Repository;

use App\Entity\GuildDaily;
use DateTime;
use DateTimeZone;
use Doctrine\Persistence\ManagerRegistry;

class GuildDailyRepository extends BaseRepository {

  public function __construct (ManagerRegistry $registry)  {
    parent::__construct($registry, GuildDaily::class);
  }

  protected function getRelationshipAlias() : string {
    return "guild";
  }

  public function getFiveMostFamousGuildDaily()
  {
    $today = new DateTime('today', new DateTimeZone('America/Sao_Paulo'));
    $guilds = $this->getFiveMostFamousGuilds($today);
    if (!$guilds){
      $yesterday = $today->modify("-1 day");
      $guilds = $this->getFiveMostFamousGuilds($yesterday);
    }

    $guildsIds = array_map(function ($item) {
      return $item->guild->id;
    }, $guilds);

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
}