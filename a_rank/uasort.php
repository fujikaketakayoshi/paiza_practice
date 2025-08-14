<?php

$data = [
    1 => ['area' => 1, 'length' => 4],
    2 => ['area' => 1, 'length' => 6],
    3 => ['area' => 2, 'length' => 3],
    4 => ['area' => 2, 'length' => 8],
];

uasort($data, function ($a, $b) {
    // 面積で比較（降順）
    if ($a['area'] !== $b['area']) {
        return $b['area'] <=> $a['area'];
    }
    // 面積が同じなら、長さで比較（降順）
    return $b['length'] <=> $a['length'];
});

print_r($data);
