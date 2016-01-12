<?php
/**
 * @file
 * Contains the game controller.
 */

namespace Life;

/**
 * Class Game
 *
 * Controller used for instantiating a new game.
 *
 * @package Life
 */
class Game {

  private $opts = [];
  private $start_time = 0;
  private $frame_count = 0;
  private $live_cell_counts = [];

  private function setDefaults(array $opts) {
    $defaults = [
      'timeout' => 5000,
      'rand_max' => 5,
      'realtime' => TRUE,
      'max_frame_count' => 0,
      'template' => NULL,
      'random' => TRUE,
      'width' => exec('tput cols'),
      'height' => exec('tput lines') - 3,
      'cell' => 'O',
      'empty' => ' ',
    ];
    if (isset($opts['template']) && !isset($opts['random'])) {
      // Disable random when template is set.
      $opts['random'] = FALSE;
    }
    $opts += $defaults;
    $this->opts += $opts;
  }

  public function __construct(array $opts) {
    $this->setDefaults($opts);
    $this->start_time = time();
    $this->grid = new Grid($this->opts['width'], $this->opts['height']);
    $this->grid->generateCells($this->opts['random'], $this->opts['rand_max']);

    if (!empty($this->opts['template'])) {
      $this->setTemplate($this->opts['template']);
    }
  }

  public function loop() {
    while (TRUE) {
      $this->frame_count++;
      if ($this->opts['realtime']) {
        $this->render();
        $this->renderFooter();
        usleep($this->opts['timeout']);
        $this->clear();
      }
      $this->newGeneration();
      if ($this->opts['max_frame_count'] && $this->frame_count >= $this->opts['max_frame_count']) {
        break;
      }
    }
    if (!$this->opts['realtime']) {
      // Draw the last frame.
      $this->clear();
      $this->render();
    }
  }

  public function setTemplate($name) {
    $template = $name . '.txt';
    $path = 'templates/' . $template;
    $file = fopen($path, 'r');
    $centerX = (int) floor($this->grid->getWidth() / 2) / 2;
    $centerY = (int) floor($this->grid->getHeight() / 2) / 2;
    $x = $centerX;
    $y = $centerY;
    while ($c = fgetc($file)) {
      if ($c == 'O') {
        $this->grid->cells[$y][$x]->setAlive();
      }
      if ($c == "\n") {
        $y++;
        $x = $centerX;
      }
      else {
        $x++;
      }
    }
    fclose($file);
  }

  /**
   * Processes a new generation for all cells.
   *
   * Base on these rules:
   * 1. Any live cell with fewer than two live neighbours dies, as if by needs caused by underpopulation.
   * 2. Any live cell with more than three live neighbours dies, as if by overcrowding.
   * 3. Any live cell with two or three live neighbours lives, unchanged, to the next generation.
   * 4. Any dead cell with exactly three live neighbours will come to life.
   */
  private function newGeneration() {
    $cells = &$this->grid->cells;
    $kill_queue = $born_queue = [];

    for ($y = 0; $y < $this->grid->getHeight(); $y++) {
      for ($x = 0; $x < $this->grid->getWidth(); $x++) {

        // All cell activity is determined by the neighbor count.
        $neighbor_count = $this->getAliveNeighborCount($x, $y);

        if ($cells[$y][$x]->isAlive() && ($neighbor_count < 2 || $neighbor_count > 3)) {
          $kill_queue[] = [$y, $x];
        }
        if ($cells[$y][$x]->isDead() && $neighbor_count === 3) {
          $born_queue[] = [$y, $x];
        }

      }
    }

    foreach ($kill_queue as $c) {
      $cells[$c[0]][$c[1]]->kill();
    }

    foreach ($born_queue as $c) {
      $cells[$c[0]][$c[1]]->setAlive();
    }
  }

  /**
   * Gets living neighbors for a cell at given coordinates.
   *
   * @param int $x
   * @param int $y
   *
   * @return int
   *   Returns the number of alive neighbors for this cell.
   */
  private function getAliveNeighborCount($x, $y) {
    $alive_count = 0;
    for ($y2 = $y - 1; $y2 <= $y + 1; $y2++) {
      if ($y2 < 0 || $y2 >= $this->grid->getHeight()) {
        // Out of range.
        continue;
      }
      for ($x2 = $x - 1; $x2 <= $x + 1; $x2++) {
        if ($x2 == $x && $y2 == $y) {
          // Current cell spot.
          continue;
        }
        if ($x2 < 0 || $x2 >= $this->grid->getWidth()) {
          // Out of range.
          continue;
        }
        if ($this->grid->cells[$y2][$x2]->isAlive()) {
          $alive_count += 1;
        }
      }
    }
    return $alive_count;
  }

  /**
   * Moves the cursor back to (0,0) to overwrite the screen.
   */
  private function clear() {
    // Move to (0,0).
    echo "\033[0;0H";
  }

  /**
   * Renders the grid in the terminal window.
   */
  private function render() {
    foreach ($this->grid->cells as $y => $row) {
      foreach ($row as $x => $cell) {
        /** @var Cell $cell */
        print ($cell->isAlive() ? $this->opts['cell'] : $this->opts['empty']);
      }
      // Done with the row.
      print "\n";
    }
  }

  /**
   * Renders a footer below the playing game.
   */
  private function renderFooter() {
    print str_repeat('_', $this->opts['width']) . "\n";
    // Return to the beginning of the line
    echo "\r";
    // Erase to the end of the line
    echo "\033[K";
    print $this->getStatus() . "\n";
  }

  /**
   * Gets a status string with various attributes.
   *
   * @return string
   */
  private function getStatus() {
    $live_cells = $this->grid->countLiveCells();
    $this->live_cell_counts[] = $live_cells;
    if (count($this->live_cell_counts) > 5) {
      $this->live_cell_counts = [];
    }
    $elapsed_time = time() - $this->start_time;
    if ($elapsed_time > 0) {
      $fps = number_format($this->frame_count / $elapsed_time, 1);
    }
    else {
      $fps = 'Calculating...';
    }
    return " Gen: {$this->frame_count} | Cells: $live_cells | Elapsed Time: {$elapsed_time}s | FPS: {$fps}";
  }

}
