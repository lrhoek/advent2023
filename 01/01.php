<?php

function calibrations(string $input) : array {
    $lines = explode(PHP_EOL, $input);
    $digitLines = filter_var_array($lines, FILTER_SANITIZE_NUMBER_INT);
    return array_map(fn($digits) => (int)(substr($digits, 0, 1) . substr($digits, -1, 1)), $digitLines);
}

function replace(string $string, int $index) : string {
    $current = substr($string, $index, 5);
    $current = str_replace(['one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'], range(1, 9), $current);
    return substr_replace($string, $current, $index, strlen($current));
}

$input = file_get_contents('input');

echo array_sum(calibrations($input)).PHP_EOL;

$input = array_reduce(range(0, strlen($input)), replace(...), $input);

echo array_sum(calibrations($input)).PHP_EOL;
