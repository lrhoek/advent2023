<?php

function algorithm(string $string) : int {
    return array_reduce(str_split($string), character(...), 0);
}

function character(int $current_value, string $character) : int {
    return ($current_value + ord($character)) * 17 % 256;
}

function hashmap(array $boxes, string $string) {
    preg_match('/([a-z]+)([-=])(\d?)/', $string, $matches);
    array_shift($matches);

    list($label, $operation, $focus) = $matches;

    $box = algorithm($label);
    $present = array_filter($boxes[$box], fn ($lens) => $lens[0] === $label);

    !($operation === "=" && !empty($present)) ?: $boxes[$box][array_keys($present)[0]] = [$label, $focus];
    !($operation === "=" && empty($present)) ?: $boxes[$box][] = [$label, $focus];
    $operation !== "-" ?: $boxes[$box] = array_udiff($boxes[$box], $present, fn ($a, $b) => $a[0] <=> $b[0]);

    return $boxes;
}

function box_focus_power(array $box, int $box_number) {
    return array_sum(array_map(fn (array $lens, int $slot_number) => $lens[1] * ($slot_number + 1) * ($box_number + 1), $box, array_keys(array_values($box))));
}

$input = file_get_contents('input');
$steps = array_map(algorithm(...), explode(',', $input));

echo array_sum($steps).PHP_EOL;

$boxes = array_reduce(explode(',', $input), hashmap(...), array_fill(0, 256, []));
$boxes = array_map(box_focus_power(...), $boxes, array_keys($boxes));

echo array_sum($boxes).PHP_EOL;