<?php

namespace App\Dto;

class ItemDto {
  public $name;
  public $values = [];

  public function __construct(string $name)
  {
    $this->name = $name;
  }

}
