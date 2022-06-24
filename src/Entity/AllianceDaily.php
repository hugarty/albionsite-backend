<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AllianceDailyRepository")
 * @ORM\Table(name="alliance_daily")
 */
class AllianceDaily
{
  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="bigint")
   */
  public $id;
  
  /**
   * @ORM\Column(type="date")
   */
  public $date;

  /**
   * @ORM\Column(type="bigint", name="alliance_id")
   */
  public $allianceId;

  /**
   * @ORM\Column(type="integer", name="guildcount")
   */
  public $guildCount;

  /**
   * @ORM\Column(type="bigint", name="membercount")
   */
  public $memberCount;
}
