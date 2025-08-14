<?php
$input_line = fgets(STDIN);
list($m, $n) = array_map('intval', explode(' ', rtrim($input_line)));

$start = [];
$goal = [];
$grid = [];
for ($i = 0; $i < $n; $i++) {
    $line = fgets(STDIN);
    $lines = explode(' ', rtrim($line));
    $tmp = [];
    foreach ($lines as $j => $l) {
        if ( $l === 's') $start = [$i, $j];
        if ( $l === 'g') $goal = [$i, $j];
        if (is_numeric($l)) $l = intval($l);
        $tmp[] = $l;
    }
    $grid[] = $tmp;
}

$visited = array_fill(0, $n, array_fill(0, $m, false));
$min_cost = null;

$queue = new SplQueue();
$queue->enqueue([$start[0], $start[1], 0]);
$visited[$start[0]][$start[1]] = true;

$dy = [-1, 0, 1, 0]; //上右下左
$dx = [0, 1, 0, -1];

while (!$queue->isEmpty()) {
    list($cy, $cx, $cost) = $queue->dequeue();
    echo $cy . ':' . $cx . ':' . $cost . "\n";
    $cost += 1;
    foreach (range(0, 3) as $dir) {
        $ny = $cy + $dy[$dir];
        $nx = $cx + $dx[$dir];
        
        if ($ny < 0 || $ny >= $n || $nx < 0 || $nx >= $m) continue;
        if ($grid[$ny][$nx] === 1) continue;
        if ($goal[0] === $ny && $goal[1] === $nx) {
            if ($min_cost === null) {
                $min_cost = $cost;
            } else {
                $min_cost = min($min_cost, $cost);
            }
            echo 'goal:' . $cost . "\n";
            continue;
        }
        if ($visited[$ny][$nx]) continue;
        $visited[$ny][$nx] = true;
        $queue->enqueue([$ny, $nx, $cost]);
    }
}

if ($min_cost === null) {
    echo 'Fail';
} else {
    echo $min_cost;
}
