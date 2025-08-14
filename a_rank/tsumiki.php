<?php
$n = intval(trim(fgets(STDIN)));
$original_blocks = [];

for ($i = 0; $i < $n; $i++) {
    list($h, $w, $d) = array_map('intval', explode(' ', trim(fgets(STDIN))));
    // 回転を考慮して2パターンを生成
    $original_blocks[] = [$h, min($w, $d), max($w, $d)];
    if ($w !== $d) {
        $original_blocks[] = [$h, max($w, $d), min($w, $d)];
    }
}

// 幅 * 奥行き が大きい順にソート（下に置くほど大きく）
usort($original_blocks, function($a, $b) {
    return ($b[1] * $b[2]) <=> ($a[1] * $a[2]);
});


$m = count($original_blocks);
$dp = array_fill(0, $m, 0);
$max_height = 0;

for ($i = 0; $i < $m; $i++) {
    $dp[$i] = $original_blocks[$i][0]; // 自分単体の高さ
    for ($j = 0; $j < $i; $j++) {
        if (
            $original_blocks[$j][1] >= $original_blocks[$i][1] &&
            $original_blocks[$j][2] >= $original_blocks[$i][2]
        ) {
            $dp[$i] = max($dp[$i], $dp[$j] + $original_blocks[$i][0]);
        }
    }
    $max_height = max($max_height, $dp[$i]);
}

echo $max_height;
