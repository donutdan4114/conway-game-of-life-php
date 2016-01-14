<?php

namespace Life;

class Grid {

  private $width;
  private $height;
  public $cells = [];

  public function __construct($width, $height) {
    $this->width = $width;
    $this->height = $height;
  }

  public function generateCells($randomize, $rand_max = 10) {
    for ($x = 0; $x < $this->width; $x++) {
      for ($y = 0; $y < $this->height; $y++) {
        if ($randomize) {
          $this->cells[$y][$x] = $this->getRandomState($rand_max);
        }
        else {
          $this->cells[$y][$x] = 0;
        }
      }
    }
    return $this;
  }

  public function countLiveCells() {
    $count = 0;
    foreach ($this->cells as $y => $row) {
      foreach ($row as $x => $cell) {
        if ($cell) {
          $count++;
        }
      }
    }
    return $count;
  }

  public function getWidth() {
    return $this->width;
  }

  public function getHeight() {
    return $this->height;
  }

  private function getRandomState($rand_max = 1) {
    return rand(0, $rand_max) === 0;
  }

}
