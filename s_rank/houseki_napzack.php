<?php
list($n, $V) = array_map('intval', explode(' ', trim(fgets(STDIN))));
$jewels = [];
for ($i = 0; $i < $n; $i++) {
    [$x, $v, $c] = array_map('intval', explode(' ', trim(fgets(STDIN))));
    // 二進法分割
    $k = 1;
    while ($c > 0) {
        $take = min($k, $c);
        $jewels[] = [$x * $take, $v * $take]; // 分割した1セット
        $c -= $take;
        $k <<= 1;
    }
}

$dp = array_fill(0, $V + 1, 0);
foreach ($jewels as [$value, $vol]) {
    for ($cap = $V; $cap >= $vol; $cap--) {
        $dp[$cap] = max($dp[$cap], $dp[$cap - $vol] + $value);
    }
}


echo max($dp) . "\n";
