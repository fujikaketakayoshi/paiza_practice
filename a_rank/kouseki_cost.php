<?php
list($n, $d) = array_map('intval', explode(' ', trim(fgets(STDIN))));
$a = array_map('intval', explode(' ', trim(fgets(STDIN))));
$x = array_map('intval', explode(' ', trim(fgets(STDIN))));

// 人数コストごとに鉱石数をカウント（コストは最大100）
$cost_count = array_fill(1, 100, 0);
foreach ($a as $cost) {
    $cost_count[$cost]++;
}

$total = 0;
foreach ($x as $day_man) {
    $bags = 0;
    for ($i = 1; $i <= 100; $i++) {
        if ($cost_count[$i] === 0) continue;
        $max_bags = intdiv($day_man, $i);
        $take = min($max_bags, $cost_count[$i]);
        $bags += $take;
        $day_man -= $take * $i;
        if ($day_man < $i) break;
    }
    $total += $bags;
}
echo $total . "\n";
