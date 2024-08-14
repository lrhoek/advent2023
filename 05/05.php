<?php

function map_sources_from_points(string $sources) : array {
    preg_match_all( '/\d+/', $sources, $matches);
    return array_map(fn ($point) => [(int) $point, (int) $point], reset($matches));
}

function map_source_from_range(string $source): array {
    list($start, $length) = array_map(intval(...), explode(" ", $source));
    return [$start, $start + $length];
}

function map_sources_from_ranges(string $sources) : array {
    preg_match_all( '/\d+ \d+/', $sources, $matches);
    return array_map(map_source_from_range(...), reset($matches));
}

function map_directive(string $directive) : array {
    list ($destinationRange, $sourceRange, $rangeLength) = array_map(intval(...), explode(" ", $directive));
    return [$sourceRange, $sourceRange + $rangeLength - 1, $destinationRange - $sourceRange];
}

function map_directives(string $map) : array {
    $map = explode(PHP_EOL, $map);
    array_shift($map);
    return array_map(map_directive(...), $map);
}

function get_destination_for_source_by_directive(int $sourceStart, int $directiveStart, int $directiveEnd, int $sourceEnd, int $directiveOffset): array {
    $source_starts_within_directive = $sourceStart >= $directiveStart && $sourceStart <= $directiveEnd;
    $source_ends_within_directive = $sourceEnd >= $directiveStart && $sourceEnd <= $directiveEnd;
    $source_contains_directive = $sourceStart <= $directiveStart && $sourceEnd >= $directiveEnd;
    $source_in_directive = $source_starts_within_directive || $source_ends_within_directive || $source_contains_directive;

    $destinationStart = max($sourceStart, $directiveStart) + $directiveOffset;
    $destinationEnd = min($sourceEnd, $directiveEnd) + $directiveOffset;

    $destination = $source_in_directive ? [[$destinationStart, $destinationEnd]] : [];

    return array_filter($destination);
}

function get_remaining_for_source_by_directive(int $sourceStart, int $directiveStart, int $sourceEnd, int $directiveEnd): array {
    $source_starts_before_directive = $sourceStart < $directiveStart;
    $before = $source_starts_before_directive ? [$sourceStart, min($directiveStart - 1, $sourceEnd)] : [];

    $source_ends_after_directive = $sourceEnd > $directiveEnd;
    $after = $source_ends_after_directive ? [max($directiveEnd + 1, $sourceStart), $sourceEnd] : [];

    return array_filter([$before, $after]);
}

function find_destination_for_source_by_directive(array $destinations, array $source, array $directive): array {
    list($sourceStart, $sourceEnd) = $source;
    list($directiveStart, $directiveEnd, $directiveOffset) = $directive;
    list($found, $remaining) = $destinations;

    array_push($found, ...get_destination_for_source_by_directive($sourceStart, $directiveStart, $directiveEnd, $sourceEnd, $directiveOffset));
    array_push($remaining, ...get_remaining_for_source_by_directive($sourceStart, $directiveStart, $sourceEnd, $directiveEnd));

    return [$found, $remaining];
}

function find_destination_for_sources_by_directive(array $state, array $directive) : array {
    list($sources, $destinations) = $state;
    list($found, $remaining) = array_reduce($sources, fn($destinations, $source) => find_destination_for_source_by_directive($destinations, $source, $directive), $destinations);
    return [$remaining, [$found, []]];
}

function find_destinations_for_sources_by_directives(array $sources, array $directives): array {
    list($remaining, $destinations) = array_reduce($directives, find_destination_for_sources_by_directive(...), [$sources, [[], []]]);
    list($found) = $destinations;
    return [...$remaining, ...$found];
}

function lowest(string $input, callable $mapper): int {
    $almanac = explode(PHP_EOL . PHP_EOL, $input);
    $sources = $mapper(array_shift($almanac));
    $maps = array_map(map_directives(...), $almanac);
    $destinations = array_reduce($maps, find_destinations_for_sources_by_directives(...), $sources);
    return min(array_column($destinations, 0));
}

$input = file_get_contents('input');
echo lowest($input, map_sources_from_points(...)).PHP_EOL;
echo lowest($input, map_sources_from_ranges(...)).PHP_EOL;