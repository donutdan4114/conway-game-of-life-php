<?php

namespace Life;

class Cell {

  private $alive = FALSE;

  public function __construct($alive = FALSE) {
    $this->alive = $alive;
  }

  public function kill() {
    $this->alive = FALSE;
  }

  public function setAlive() {
    $this->alive = TRUE;
  }

  public function isAlive() {
    return $this->alive === TRUE;
  }

  public function isDead() {
    return !$this->isAlive();
  }

}
