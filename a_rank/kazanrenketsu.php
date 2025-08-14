<?php
fscanf(STDIN, "%d %d", $n, $r);

$positions = [];
for ($i = 0; $i < $n; $i++) {
    fscanf(STDIN, "%d %d", $x, $y);
    $positions[] = [$x, $y];
}

// Union-Find の初期化
$parent = range(0, $n - 1);

function find($x) {
    global $parent;
    if ($parent[$x] !== $x) {
        $parent[$x] = find($parent[$x]);
    }
    return $parent[$x];
}

function unite($x, $y) {
    global $parent;
    $px = find($x);
    $py = find($y);
    if ($px !== $py) {
        $parent[$py] = $px;
    }
}

// 各火山同士の距離を調べて、距離が 2R 以下なら結合
for ($i = 0; $i < $n; $i++) {
    echo "火山{$i} → 代表: " . find($i) . "\n";
    for ($j = $i + 1; $j < $n; $j++) {
        $dx = $positions[$i][0] - $positions[$j][0];
        $dy = $positions[$i][1] - $positions[$j][1];
        if (sqrt($dx * $dx + $dy * $dy) <= 2 * $r) {
            unite($i, $j);
        }
    }
}

// 代表の数（重複を除く）を数える
$roots = [];
for ($i = 0; $i < $n; $i++) {
    $roots[] = find($i);
}
echo count(array_unique($roots)) . "\n";
