# Conway's Game of Life in PHP
Created by: Daniel Pepin

[What is the Game of Life?](https://en.wikipedia.org/wiki/Conway%27s_Game_of_Life)

View help documentation:

```
> php life.php help

  Conway's Game of Life in PHP
  Created by: Daniel Pepin
 ---------------------------------
  Available options to change:
  - random (default=1): Whether to populate grid with random cells
  - rand_max (default=5): Chances of a cell being alive. Lower is more alive cells
  - timeout (default=5000): Number of microseconds between frame renders
  - realtime (default=1): Whether to render the grid in realtime, or just run calculations
  - max_frame_count (default=0): The max number of frames to render
  - template: Loads a template from a txt file. See /templates folder
  - cell (default=O): Alive cell character
  - empty: Dead cell character
  - width (default=TERM_WIDTH): Grid width
  - height (default=TERM_HEIGHT): Grid height

  Options are applied in a query string format. Examples:
  - php life.php 'template=glider_gun'
  - php life.php 'timeout=250000&rand_max=5&max_frame_count=1000'
```

Simply run with `php life.php`
![Imgur](http://i.imgur.com/P5JNPtj.gif)

Example `glider_gun` template: `php life.php "template=glider_gun"`: 
![Imgur](http://i.imgur.com/onytKlx.gif)
