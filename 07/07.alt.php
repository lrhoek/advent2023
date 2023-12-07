<?php

function map_hand(string $hand, callable $rules) : array {
    list($hand, $bid) = explode(' ', $hand);
    list($modified_hand, $jack) = $rules($hand);
    return [hand_strength($modified_hand).hand_order($hand, $jack), (int) $bid];
}

function joker_rules(string $hand) : array {
    $card_counts = array_count_values(str_split(str_replace('J', '', $hand)));
    arsort($card_counts);
    $hand = empty($card_counts) ? $hand : str_replace('J', key($card_counts), $hand);
    return [$hand, '1'];
}

function normal_rules(string $hand) : array {
    return [$hand, 'C'];
}

function hand_strength(string $hand) : int {
    $value_counts = array_count_values(str_split($hand));
    return 15 * max($value_counts) - count($value_counts);
}

function hand_order(string $hand, string $jack) : string {
    return str_replace(['A', 'K', 'Q', 'J', 'T'], ['F', 'E', 'D', $jack, 'B'], $hand);
}

function compare_hands(array $a, array $b) : int {
    return strcmp($a[0], $b[0]);
}

function winnings(array $hands, callable $rules) : int {
    $hands = array_map(fn ($hand) => map_hand($hand, $rules), $hands);
    usort($hands, compare_hands(...));
    $winnings = array_map(win(...), $hands, array_keys($hands));
    return array_sum($winnings);
}

function win(array $hand, int $rank) : int {
    return ($rank + 1) * $hand[1];
}

$hands = explode(PHP_EOL, file_get_contents('input'));

echo winnings($hands, normal_rules(...)).PHP_EOL;
echo winnings($hands, joker_rules(...)).PHP_EOL;