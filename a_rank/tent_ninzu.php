<?php
$n = intval(trim(fgets(STDIN)));
$a = [];
for ($i = 0; $i < $n; $i++) {
    $a[] = intval(trim(fgets(STDIN)));
}

$dp = array_fill(0, $n + 1, 0); // dp[i]: i番目以降で得られる最大合計

for ($i = $n - 1; $i >= 0; $i--) {
    // 1桁を使うパターン
    $res1 = $a[$i] + $dp[$i + 1];

    // 2桁を使うパターン（存在する場合）
    $res2 = 0;
    if ($i + 1 < $n) {
        $res2 = $a[$i] * 10 + $a[$i + 1] + $dp[$i + 2];
    }

    $dp[$i] = max($res1, $res2);
}

echo $dp[0] . "\n";
