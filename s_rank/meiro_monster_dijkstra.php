<?php
list($h, $w) = array_map('intval', explode(' ', trim(fgets(STDIN))));

$grid = [];
$start = $goal = null;
for ($i = 0; $i < $h; $i++) {
    $line = str_split(trim(fgets(STDIN)));
    foreach ($line as $j => &$c) {
        if ($c === 'S') {
            $start = [$i, $j];
            $c = 0;
        } elseif ($c === 'G') {
            $goal = [$i, $j];
            $c = 0;
        } else {
            $c = intval($c);
        }
    }
    $grid[] = $line;
}

$dy = [-1, 0, 1, 0];
$dx = [0, 1, 0, -1];

// 優先度付きキュー (最小ヒープ)
$dist = array_fill(0, $h, array_fill(0, $w, PHP_INT_MAX));
$dist[$start[0]][$start[1]] = 0;
$pq = new SplPriorityQueue();
$pq->setExtractFlags(SplPriorityQueue::EXTR_BOTH);
$pq->insert($start, 0); // PHPのPriorityQueueは大きい順なので、priorityは負数で

while (!$pq->isEmpty()) {
    $current = $pq->extract();
    [$y, $x] = $current['data'];
    $cost = -$current['priority'];

    if ($cost > $dist[$y][$x]) continue;
    if ($y === $goal[0] && $x === $goal[1]) {
        echo 'Goal:' . $dist[$y][$x] . "\n";
        break;
    }

    for ($dir = 0; $dir < 4; $dir++) {
        $ny = $y + $dy[$dir];
        $nx = $x + $dx[$dir];
        if ($ny < 0 || $ny >= $h || $nx < 0 || $nx >= $w) continue;
        $ncost = $cost + $grid[$ny][$nx];
        if ($ncost < $dist[$ny][$nx]) {
            $dist[$ny][$nx] = $ncost;
            $pq->insert([$ny, $nx], -$ncost);
        }
    }
}

echo $dist[$goal[0]][$goal[1]], "\n";
