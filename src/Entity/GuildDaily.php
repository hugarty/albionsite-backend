<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GuildDailyRepository")
 * @ORM\Table(name="guild_daily")
 */
class GuildDaily
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
   * @ORM\ManyToOne(targetEntity="Guild", fetch="LAZY")
   */
  public $guild;

  /**
   * @ORM\Column(type="bigint")
   */
  public $fame;

  /**
   * @ORM\Column(type="bigint", name="killfame")
   */
  public $killFame;

  /**
   * @ORM\Column(type="bigint", name="deathfame")
   */
  public $deathFame;

  /**
   * @ORM\Column(type="bigint", name="gvgkills")
   */
  public $gvgKills;

  /**
   * @ORM\Column(type="bigint", name="gvgdeaths")
   */
  public $gvgDeaths;

  /**
   * @ORM\Column(type="bigint")
   */
  public $kills;

  /**
   * @ORM\Column(type="bigint")
   */
  public $deaths;

  /**
   * @ORM\Column(type="string")
   */
  public $ratio;

  /**
   * @ORM\Column(type="integer", name="membercount")
   */
  public $memberCount;

}
