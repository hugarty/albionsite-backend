<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Guild
{
  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="bigint")
   */
  public $id;
  /**
   * @ORM\Column(type="string", name="albion_id")
   */
  public $albionId;
  /**
   * @ORM\Column(type="string")
   */
  public $name;
  /**
   * @ORM\Column(type="string", name="alliance_albion_id")
   */
  public $allianceAlbionId;
}
