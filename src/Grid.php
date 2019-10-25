<?php
/**
 * @file
 * Contains the Grid class.
 */

namespace Life;

/**
 * Class Grid
 *
 * Provides the container that cells live in.
 *
 * @package Life
 */
class Grid {

  private $width;

  private $height;

  public $cells = [];

  /**
   * Grid constructor.
   *
   * Creates a new grid with the specified width and height.
   *
   * @param int $width
   * @param int $height
   */
  public function __construct($width, $height) {
    $this->width = $width;
    $this->height = $height;
  }

  /**
   * Fills the grid with cells.
   *
   * @param bool $randomize
   *   Whether we should start with empty cells or randomize "alive" cells.
   * @param int $rand_max
   *   Configurable rand_max option which determines how many random "alive" cells we start with.
   *
   * @return $this
   */
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

  /**
   * Count all the live cells.
   *
   * @return int
   */
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

  /**
   * Get the grid width.
   *
   * @return int
   */
  public function getWidth() {
    return $this->width;
  }

  /**
   * Get the grid height.
   *
   * @return int
   */
  public function getHeight() {
    return $this->height;
  }

  /**
   * Get a random state for a cell.
   *
   * @param int $rand_max
   *   Lower values means more "alive" cells.
   *
   * @return bool
   */
  private function getRandomState($rand_max = 1) {
    return rand(0, $rand_max) === 0;
  }
}
