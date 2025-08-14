<?php
// グリッド（. は通れるマス）
$grid = [
    ['S', 3, 'G'],
    [1, 3, 1],
    [1, 1, 1],
];

// スタートとゴール
$start = [0, 0];
$goal  = [0, 2];

// 優先度付きキュー（最小コスト順）
$queue = new SplPriorityQueue();
$queue->setExtractFlags(SplPriorityQueue::EXTR_BOTH);

// dist配列初期化
$dist = [];
foreach ($grid as $y => $row) {
    foreach ($row as $x => $cell) {
        $dist[$y][$x] = INF;
    }
}

// スタート登録
$dist[$start[0]][$start[1]] = 0;
$queue->insert([$start[0], $start[1]], -0);

$dy = [-1, 1, 0, 0];
$dx = [0, 0, -1, 1];
$step = 0;

while (!$queue->isEmpty()) {
    echo "=== Step $step ===\n";

    // キューの中身を表示
    $tmp = clone $queue;
    $qitems = [];
    foreach ($tmp as $item) {
        $qitems[] = '(' . implode(',', $item['data']) . ' cost=' . -$item['priority'] . ')';
    }
    echo "Queue: " . implode(' ', $qitems) . "\n";

    // 最小コストの座標を取り出す
    $current = $queue->extract();
    [$y, $x] = $current['data'];
    $cost = -$current['priority'];

    echo "Pop: ($y,$x) cost=$cost\n";

    // 古いデータはスキップ
    if ($cost > $dist[$y][$x]) {
        echo "Skip (old cost)\n\n";
        $step++;
        continue;
    }

    // ゴール到達
    if ([$y, $x] == $goal) {
        echo "Reached GOAL with cost=$cost\n\n";
    }

    // 隣接4マス探索
    for ($i = 0; $i < 4; $i++) {
        $ny = $y + $dy[$i];
        $nx = $x + $dx[$i];
        if (!isset($grid[$ny][$nx])) continue; // 範囲外

        $new_cost = $cost + (is_numeric($grid[$ny][$nx]) ? $grid[$ny][$nx]: 0);
        if ($new_cost < $dist[$ny][$nx]) {
            $dist[$ny][$nx] = $new_cost;
            $queue->insert([$ny, $nx], -$new_cost);
            echo "Update: ($ny,$nx) cost=$new_cost\n";
        }
    }

    echo "\n";
    $step++;
}
