<?php
/**
 * @file
 * Base entry point for the Game of Life.
 *
 * Run "php life.php help" to view the help documentation.
 */

namespace Life;

include 'src/Cell.php';
include 'src/Game.php';
include 'src/Grid.php';

$opts = [];

if (isset($argv[1]) && $argv[1] == 'help') {
  // Check for 'help' argument.
  print PHP_EOL;
  print "  Conway's Game of Life in PHP" . PHP_EOL;
  print "  Created by: Daniel Pepin" . PHP_EOL;
  print " ---------------------------------" . PHP_EOL;
  print "  Available options to change:" . PHP_EOL;
  print "  - random (default=1): Whether to populate grid with random cells" . PHP_EOL;
  print "  - rand_max (default=5): Chances of a cell being alive. Lower is more alive cells" . PHP_EOL;
  print "  - timeout (default=5000): Number of microseconds between frame renders" . PHP_EOL;
  print "  - realtime (default=1): Whether to render the grid in realtime, or just run calculations" . PHP_EOL;
  print "  - max_frame_count (default=0): The max number of frames to render" . PHP_EOL;
  print "  - template: Loads a template from a txt file. See /templates folder" . PHP_EOL;
  print "  - cell (default=O): Alive cell character" . PHP_EOL;
  print "  - empty: Dead cell character" . PHP_EOL;
  print "  - width (default=TERM_WIDTH): Grid width" . PHP_EOL;
  print "  - height (default=TERM_HEIGHT): Grid height" . PHP_EOL;
  print PHP_EOL;
  print "  Options are applied in a query string format. Examples:" . PHP_EOL;
  print "  - php life.php 'template=glider_gun'" . PHP_EOL;
  print "  - php life.php 'timeout=250000&rand_max=5&max_frame_count=1000'" . PHP_EOL;
  print PHP_EOL;
  die();
}

if (isset($argv[1])) {
  // Populate options from the input arguments.
  parse_str($argv[1], $opts);
}

$game = new Game($opts);
$game->loop();

print 'Bye!';
