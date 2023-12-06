<?php

function find_destination_in_range($source, $range) : ?int {
    list ($destinationRange, $sourceRange,  $rangeLength) = $range;
    return ($source >= $sourceRange && $source < $sourceRange + $rangeLength ? $source - $sourceRange + $destinationRange : null);
}

function find_destination_in_map($source, $map) : int {
    $range = array_shift($map);
    $destination = is_null($range) ? $source : find_destination_in_range($source, $range);
    return $destination ?? find_destination_in_map($source, $map);
}

function find_destination_in_almanac($source, $almanac) : int {
    return array_reduce($almanac, find_destination_in_map(...), $source);
}

function build_map($map) : array {
    $map = explode(PHP_EOL, $map);
    array_shift($map);
    return array_map(fn ($range) => explode(" ", $range), $map);
}

function build_seeds($seeds) : array {
    $seeds = explode(" ", $seeds);
    array_shift($seeds);
    return $seeds;
}

$input = file_get_contents('input');
$almanac = explode(PHP_EOL.PHP_EOL, $input);
$seeds = build_seeds(array_shift($almanac));
$almanac = array_map(build_map(...), $almanac);
$destinations = array_map(fn ($map) => find_destination_in_almanac($map, $almanac), $seeds);

echo min($destinations).PHP_EOL;
