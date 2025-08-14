<?php
$input_line = fgets(STDIN);
$n = rtrim($input_line);
$map = [];
while ($line = fgets(STDIN)) {
    $map[] = array_map('intval', explode(' ', rtrim($line)));
}

$arr = range(0, $n - 1);
$combinations = dfs($arr, 3);

function dfs($arr, $r, $start = 0, $prefix = [], &$results = []) {
    if ($r === 0) {
        $results[] = $prefix;
        return;
    }
    for ($i = $start; $i <= count($arr) - $r; $i++) {
        dfs($arr, $r - 1, $i + 1, array_merge($prefix, [$arr[$i]]), $results);
    }
    return $results;
}

function cross($x1, $y1, $x2, $y2) {
    return $x1 * $y2 - $y1 * $x2;
}

function isInsideTriangle($a, $b, $c, $p) {
    list($ax, $ay) = $a;
    list($bx, $by) = $b;
    list($cx, $cy) = $c;
    list($px, $py) = $p;

    $ab = cross($bx - $ax, $by - $ay, $px - $ax, $py - $ay);
    $bc = cross($cx - $bx, $cy - $by, $px - $bx, $py - $by);
    $ca = cross($ax - $cx, $ay - $cy, $px - $cx, $py - $cy);

    // 内積がすべて正かすべて負、または0を含んでもよい（辺上もOK）
    return ($ab >= 0 && $bc >= 0 && $ca >= 0) || ($ab <= 0 && $bc <= 0 && $ca <= 0);
}

$maxCount = 0;

foreach ($combinations as $c) {
    $a = $map[$c[0]];
    $b = $map[$c[1]];
    $c_pt = $map[$c[2]];

    $count = 0;
    foreach ($map as $i => $p) {
        if (in_array($i, $c)) continue; // 頂点は除く
        if (isInsideTriangle($a, $b, $c_pt, $p)) {
            $count++;
        }
    }

    if ($count > $maxCount) {
        $maxCount = $count;
    }
}

echo $maxCount . PHP_EOL;
