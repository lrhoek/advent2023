<?php

$input = file_get_contents('input');

$lines = explode(PHP_EOL, $input);

$digitLines = filter_var_array($lines, FILTER_SANITIZE_NUMBER_INT);

$calibrations = array_map(fn ($digits) => (int) (substr($digits, 0, 1) . substr($digits, -1, 1)), $digitLines);

echo array_sum($calibrations).PHP_EOL;

function replace($string, $index) {
    $current = substr($string, $index, 5);
    $current = str_replace(['one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'], range(1, 9), $current);
    return substr_replace($string, $current, $index, strlen($current));
}

$input = array_reduce(range(0, strlen($input)), replace(...), $input);

$lines = explode(PHP_EOL, $input);

$digitLines = filter_var_array($lines, FILTER_SANITIZE_NUMBER_INT);

$calibrations = array_map(fn ($digits) => (int) (substr($digits, 0, 1) . substr($digits, -1, 1)), $digitLines);

echo array_sum($calibrations).PHP_EOL;
