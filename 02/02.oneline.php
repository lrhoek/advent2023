<?php echo array_sum(array_keys($possible = array_filter($games = array_map(fn ($game) => array_map(max(...), array_reduce(regex_match("/(\d+) (green|red|blue)/", $game), fn ($matches, $match) => array_merge_recursive($matches, [$match[2] => [(int) $match[1]]]), ["red" => [], "green" => [], "blue" => []])), explode(PHP_EOL, file_get_contents('input'))), fn ($game) => $game["red"] <= 12 && $game["green"] <= 13 && $game["blue"] <= 14))) + count($possible).PHP_EOL.array_sum(array_map(array_product(...), $games)).PHP_EOL;function regex_match($p, $s) { preg_match_all($p, $s, $m, 2); return $m; }