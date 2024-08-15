<?php

function get_numbers(string $numbers) {
    preg_match_all( '/\d+/', $numbers, $matches);
    return array_map(intval(...), reset($matches));
}

function discriminant($a, $b, $c) {
    return sqrt($b ** 2 - 4 * $a * $c);
}

function roots($a, $b, $c) {
    $d = discriminant($a, $b, $c);
    return [(-$b + $d) / 2 * $a, (-$b - $d) / 2 * $a];
}

function winners($time, $distance) {
    $roots = roots(-1, $time, -($distance + 1));
    return [ceil($roots[0]), floor($roots[1])];
}

function options($min, $max) {
    return $max - $min + 1;
}

$input = file_get_contents('input');
$input = explode(PHP_EOL, $input);
$input = array_map(get_numbers(...), $input);

$races = array_map(null, ...$input);
$winners = array_map(fn ($race) => winners(...$race), $races);
$options = array_map(fn ($winner) => options(...$winner), $winners);

echo array_product($options).PHP_EOL;

$race = array_map(fn ($number) => implode("", $number), $input);
$winner = winners(...$race);
$options = options(...$winner);

echo $options.PHP_EOL;