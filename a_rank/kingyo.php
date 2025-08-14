<?php
list($n, $x) = array_map('intval', explode(' ', trim(fgets(STDIN))));

$kingyos = [];
for ($i = 0; $i < $n; $i++) {
    list($w, $r) = array_map('intval', explode(' ', trim(fgets(STDIN))));
    $kingyos[] = ['w' => $w, 'r' => $r];
}

// dp[i] = 耐久値 i を使ったときの最大美しさ
$dp = array_fill(0, $x, -1);
$dp[0] = 0;

foreach ($kingyos as $k) {
    $w = $k['w'];
    $r = $k['r'];

    // 後ろから更新しないと同じ金魚を複数回選んでしまう
    for ($i = $x - 1; $i >= 0; $i--) {
        if ($dp[$i] !== -1 && $i + $w < $x) {
            $dp[$i + $w] = max($dp[$i + $w], $dp[$i] + $r);
            var_dump($dp);
        }
    }
}

echo max($dp);
