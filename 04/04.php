<?php

function winners(string $card) : int {

    $numbers = explode("|", explode(":", $card)[1]);
    $sequences = array_map(fn ($sequence) => preg_split('/\s+/', $sequence, -1, 1), $numbers);
    return count(array_intersect(...$sequences));
}

function expand(array $stack, int $i, int $key) : array {

    $stack[$key+$i] += $stack[$key];
    return $stack;
}

function stack(array $stack, int $key, array $winners) : array {

    $range = $winners[$key] > 0 ? range(1, $winners[$key]) : [];
    return array_reduce($range, fn($stack, $i) => expand($stack, $i, $key), $stack);
}

$input = file_get_contents('input');
$cards = explode(PHP_EOL, $input);
$winners = array_map(winners(...), $cards);

$points = array_map(fn($card) => !empty($card) ? pow(2, $card - 1) : 0, $winners);
$stack = array_reduce(array_keys($winners), fn ($stack, $key) => stack($stack, $key, $winners), array_fill(0, count($winners), 1));

echo array_sum($points).PHP_EOL;
echo array_sum($stack).PHP_EOL;