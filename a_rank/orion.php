<?php
list($h, $w) = array_map('intval', explode(' ', trim(fgets(STDIN))));

$map = [];
for ($i = 0; $i < $h; $i++) {
    $map[] = str_split(trim(fgets(STDIN)));
}

// 累積和の構築
$acc = array_fill(0, $h + 1, array_fill(0, $w + 1, 0));
for ($i = 0; $i < $h; $i++) {
    for ($j = 0; $j < $w; $j++) {
        $acc[$i + 1][$j + 1] = ($map[$i][$j] === '*' ? 1 : 0)
                             + $acc[$i][$j + 1]
                             + $acc[$i + 1][$j]
                             - $acc[$i][$j];
    }
}

function get_star_count($acc, $i1, $j1, $i2, $j2) {
    if ($i1 > $i2 || $j1 > $j2) return 0;
    return $acc[$i2 + 1][$j2 + 1]
         - $acc[$i1][$j2 + 1]
         - $acc[$i2 + 1][$j1]
         + $acc[$i1][$j1];
}

$count = 0;
for ($i = 0; $i < $h; $i++) {
    for ($j = 0; $j <= $w - 3; $j++) {
        if ($map[$i][$j] === '*' && $map[$i][$j + 1] === '*' && $map[$i][$j + 2] === '*') {

            $a = get_star_count($acc, 0, 0, $i - 1, $j - 1);
            $b = get_star_count($acc, 0, $j + 3, $i - 1, $w - 1);
            $c = get_star_count($acc, $i + 1, 0, $h - 1, $j - 1);
            $d = get_star_count($acc, $i + 1, $j + 3, $h - 1, $w - 1);

            $count += $a * $b * $c * $d;
        }
    }
}

echo $count . "\n";
