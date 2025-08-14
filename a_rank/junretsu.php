<?php

function generateOver($target, $current = [], $sum = 0) {
    $results = [];

    if ($sum >= $target) {
        return [$current]; // 超えた時点の組み合わせを返す
    }

    $results = array_merge($results, generateOver($target, array_merge($current, [3]), $sum + 3));
    $results = array_merge($results, generateOver($target, array_merge($current, [5]), $sum + 5));

    return $results;
}

$overPatterns = generateOver(20);

var_dump($overPatterns);