<?php
// グリッド（. は通れるマス、# は壁）
$grid = [
    ['S', '.', '.', 'G'],
    ['.', '#', '#', '.'],
    ['S', '.', '.', 'G'],
];

// スタートとゴール座標
$start1 = [0, 0];
$goal1  = [0, 3];
$start2 = [2, 0];
$goal2  = [2, 3];

// 優先度付きキュー（最小コスト順）
$queue = new SplPriorityQueue();
$queue->setExtractFlags(SplPriorityQueue::EXTR_BOTH);

// dist配列（大きな値で初期化）
$dist = [];
foreach ($grid as $y => $row) {
    foreach ($row as $x => $cell) {
        $dist[$y][$x] = INF;
    }
}

// スタート2つを登録
$dist[$start1[0]][$start1[1]] = 0;
$queue->insert([$start1[0], $start1[1]], -0); // SplPriorityQueue は大きい値優先なので符号反転
$dist[$start2[0]][$start2[1]] = 0;
$queue->insert([$start2[0], $start2[1]], -0);

$dy = [-1, 1, 0, 0];
$dx = [0, 0, -1, 1];
$step = 0;

while (!$queue->isEmpty()) {
    echo "=== Step $step ===\n";
    // キューの中身を確認（コピーしてdump）
    $tmp = clone $queue;
    echo "Queue: ";
    $qitems = [];
    foreach ($tmp as $item) {
        $qitems[] = '(' . implode(',', $item['data']) . ' cost=' . -$item['priority'] . ')';
    }
    echo implode(' ', $qitems) . "\n";

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

    // ゴール判定（複数のゴールをチェック）
    if (([$y,$x] == $goal1) || ([$y,$x] == $goal2)) {
        echo "Reached goal at ($y,$x) with cost=$cost\n\n";
        // 続けるけど、distは確定している
    }

    // 隣接4マスを探索
    for ($i = 0; $i < 4; $i++) {
        $ny = $y + $dy[$i];
        $nx = $x + $dx[$i];
        if (!isset($grid[$ny][$nx])) continue; // 範囲外
        if ($grid[$ny][$nx] === '#') continue; // 壁

        $new_cost = $cost + 1;
        if ($new_cost < $dist[$ny][$nx]) {
            $dist[$ny][$nx] = $new_cost;
            $queue->insert([$ny, $nx], -$new_cost);
            echo "Update: ($ny,$nx) cost=$new_cost\n";
        }
    }
    echo "\n";
    $step++;
}
