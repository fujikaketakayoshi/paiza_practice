<?php
function getCombinations($arr, $r) {
    $results = [];

    $combine = function($start, $combo) use (&$combine, &$results, $arr, $r) {
        if (count($combo) === $r) {
            $results[] = $combo;
            return;
        }

        for ($i = $start; $i < count($arr); $i++) {
            $combine($i + 1, array_merge($combo, [$arr[$i]]));
        }
    };

    $combine(0, []);
    return $results;
}

// 入力配列
$input = [1, 2, 3, 4];
$half = intdiv(count($input), 2);
$combis = getCombinations($input, $half);
var_dump($combis);