<?php

function map_hand(string $hand) : array {
    list($hand, $bid) = explode(' ', $hand);

    $order = str_replace(['A', 'K', 'Q', 'J', 'T'], ['F', 'E', 'D', 'C', 'B'], $hand);
    $strength = hand_strength($hand);

    $card_counts = array_count_values(str_split(str_replace('J', '', $hand)));
    arsort($card_counts);
    $joker_hand = empty($card_counts) ? $hand : str_replace('J', key($card_counts), $hand);

    $joker_order = str_replace('C', '1', $order);
    $joker_strength = hand_strength($joker_hand);

    return [$strength, $order, $joker_strength, $joker_order, $bid];
}

function hand_strength(string $hand) : int {
    $value_counts = array_count_values(str_split($hand));
    return 10 * max($value_counts) - count($value_counts);
}

function compare_hands(array $a, array $b) : int {
    return $a[0] === $b[0] ? strcmp($a[1], $b[1]) : $a[0] <=> $b[0];
}

function compare_hands_with_jokers(array $a, array $b) : int {
    return $a[2] === $b[2] ? strcmp($a[3], $b[3]) : $a[2] <=> $b[2];
}

function winnings(array $hands) : int {
    $winnings = array_map(fn (int $strength, int $key) => ($key + 1) * $strength, array_column($hands, 4), array_keys($hands));
    return array_sum($winnings);
}

$hands = array_map(map_hand(...), explode(PHP_EOL, file_get_contents('input')));

usort($hands, compare_hands(...));
echo winnings($hands).PHP_EOL;

usort($hands, compare_hands_with_jokers(...));
echo winnings($hands).PHP_EOL;