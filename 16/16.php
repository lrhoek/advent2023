<?php

function up($y, $x) {
    return [$y-1, $x, 'up'];
}

function down($y, $x) {
    return [$y+1, $x, 'down'];
}

function left($y, $x) {
    return [$y, $x-1, 'left'];
}

function right($y, $x) {
    return [$y, $x+1, 'right'];
}

function move(array &$grid, array $moves = [[0, 0, 'right']], array &$visited = []) {
    list($y, $x, $direction) = array_shift($moves);
    $tile = $grid[$y][$x] ?? 'x';

    $tile === 'x' ?: $visited[] = [$y, $x, $direction];

    $new = [];

    !($tile === '/' && $direction === 'right') ?: $new[] = up($y, $x);
    !($tile === '/' && $direction === 'left') ?: $new[] = down($y, $x);
    !($tile === '/' && $direction === 'up') ?: $new[] = right($y, $x);
    !($tile === '/' && $direction === 'down') ?: $new[] = left($y, $x);

    !($tile === '\\' && $direction === 'right') ?: $new[] = down($y, $x);
    !($tile === '\\' && $direction === 'left') ?: $new[] = up($y, $x);
    !($tile === '\\' && $direction === 'up') ?: $new[] = left($y, $x);
    !($tile === '\\' && $direction === 'down') ?: $new[] = right($y, $x);

    !($tile === '|' && in_array($direction, ["left", "right"])) ?: array_push($new, up($y, $x), down($y, $x));
    !($tile === '|' && in_array($direction, ["up", "down"])) ?: $tile = '.';

    !($tile === '-' && in_array($direction, ["up", "down"])) ?: array_push($new, left($y, $x), right($y, $x));
    !($tile === '-' && in_array($direction, ["left", "right"])) ?: $tile = '.';

    !($tile === '.' && $direction === 'right') ?: $new[] = right($y, $x);
    !($tile === '.' && $direction === 'left') ?: $new[] = left($y, $x);
    !($tile === '.' && $direction === 'up') ?: $new[] = up($y, $x);
    !($tile === '.' && $direction === 'down') ?: $new[] = down($y, $x);

    array_push($moves, ...array_filter($new, fn($move) => !in_array($move, $visited)));
    return empty($moves) ? $visited : move($grid, $moves, $visited);
}

function multimove($highest, $move, &$grid) {
    $result = move($grid, [$move]);
    $result = array_map(fn ($visit) => [$visit[0], $visit[1]], $result);
    $result = array_unique($result, SORT_REGULAR);
    return max($highest, count($result));
}

$input = file_get_contents('input');
$grid = array_map(str_split(...), explode(PHP_EOL, $input));

$result = multimove(0, [0, 0, 'right'], $grid);
echo $result.PHP_EOL;

$moves[] = array_map(fn ($i) => [0, $i, "down"], range(0, count($grid[0])-1));
$moves[] = array_map(fn ($i) => [count($grid)-1, $i, "up"], range(0, count($grid[0])-1));
$moves[] = array_map(fn ($i) => [$i, 0, "right"], range(0, count($grid)-1));
$moves[] = array_map(fn ($i) => [$i, count($grid[0])-1, "left"], range(0, count($grid)-1));
$moves = array_merge(...$moves);

$result = array_reduce($moves, fn ($highest, $move) => multimove($highest, $move, $grid), 0);
echo $result.PHP_EOL;