<?php

function game($game) {
    preg_match_all("/(\d+) (green|red|blue)/", $game, $matches, PREG_SET_ORDER);
    return array_reduce($matches, map(...), ["red" => [], "green" => [], "blue" => []]);
}

function map($matches, $match) {
    $matches[$match[2]][] = (int) $match[1];
    return $matches;
}

$games = array_map(game(...), explode(PHP_EOL, file_get_contents('input')));

$possible = array_filter($games, fn ($game) => max($game["red"]) <= 12 && max($game["green"]) <= 13 && max($game["blue"]) <= 14);

$fewest = array_map(fn ($game) => max($game["red"]) * max($game["green"]) * max($game["blue"]), $games);

echo array_sum(array_keys($possible)) + count($possible).PHP_EOL;
echo array_sum($fewest).PHP_EOL;