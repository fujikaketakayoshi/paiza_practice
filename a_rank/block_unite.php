<?php
$input_line = fgets(STDIN);
$n = intval(rtrim($input_line));
$line = fgets(STDIN);
$blocks = array_map('intval', explode(' ', rtrim($line)));

// 各サイズのブロックの個数を数える
$counts = [];
foreach ($blocks as $a) {
    $counts[$a] = ($counts[$a] ?? 0) + 1;
}

// 小さい順に繰り上げていくためにソートしてキーリストを作る
$keys = array_keys($counts);
sort($keys);

$idx = 0;
while ($idx < count($keys)) {
    $size = $keys[$idx];
    $cnt = $counts[$size];

    if ($cnt >= 2) {
        $pairs = intdiv($cnt, 2);
        $counts[$size] %= 2;

        $next_size = $size * 2;
        if (!isset($counts[$next_size])) {
            $counts[$next_size] = 0;
            $keys[] = $next_size; // 新しいキーも対象にする！
        }
        $counts[$next_size] += $pairs;
    }

    $idx++;
}

// 残ったブロックの種類数を数える
$result = 0;
foreach ($counts as $num) {
    if ($num > 0) {
        $result++;
    }
}

echo $result;
