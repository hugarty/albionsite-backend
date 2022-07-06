<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AllianceWeeklyRepository")
 * @ORM\Table(name="alliance_weekly")
 */
class AllianceWeekly
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
   * @ORM\ManyToOne(targetEntity="Alliance", fetch="LAZY")
   */
  public $alliance;
  
  /**
   * @ORM\Column(type="integer")
   */
  public $territories;

  /**
   * @ORM\Column(type="integer")
   */
  public $castles;

}
