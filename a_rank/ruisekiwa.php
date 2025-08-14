<?php
//配列 [3, 1, 4, 1, 5, 9] から、区間 [1,3] の和、[2,5] の和、[0,2] の和を出力せよ。
$data = [3, 1, 4, 1, 5, 9];
$n = count($data);

$acc = array_fill(0, $n + 1, 0);

for ($i = 0; $i < $n; $i++) {
    $acc[$i+1] = $acc[$i] + $data[$i];
}

echo $acc[4] - $acc[1] . "\n";
echo $acc[6] - $acc[2] . "\n";
echo $acc[3] - $acc[0] . "\n";
