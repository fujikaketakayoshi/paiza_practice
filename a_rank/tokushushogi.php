<?php
$input_line = fgets(STDIN);
list($h, $w, $k) = array_map('intval', explode(' ', rtrim($input_line)));

$a_dirs = [
    [-1, -1], // 左上
    [-1, 0],  // 上
    [-1, 1],  // 右上
    [1, -1],  // 左下
    [1, 1],   // 右下
];

$b_dirs = [
    [1, -2],  // 左左下
    [-1, 2],  // 右右上
];

// dp[turn][y][x][piece_type] = その状態に到達可能かどうか
// piece_type: 0=A, 1=B
$dp = array_fill(0, $k + 1, array_fill(0, 9, array_fill(0, 9, array_fill(0, 2, false))));

// 初期状態：(h-1, w-1)に駒Aが配置
$start_y = $h - 1;
$start_x = $w - 1;
$dp[0][$start_y][$start_x][0] = true;

// BFS的にDP更新
for ($turn = 0; $turn < $k; $turn++) {
    for ($y = 0; $y < 9; $y++) {
        for ($x = 0; $x < 9; $x++) {
            // 駒Aの処理
            if ($dp[$turn][$y][$x][0]) {
                foreach ($a_dirs as $dir) {
                    $ny = $y + $dir[0];
                    $nx = $x + $dir[1];
                    if ($ny >= 0 && $ny < 9 && $nx >= 0 && $nx < 9) {
                        // 駒Aのまま移動
                        $dp[$turn + 1][$ny][$nx][0] = true;
                        // 駒Bに変身して移動
                        $dp[$turn + 1][$ny][$nx][1] = true;
                    }
                }
            }
            
            // 駒Bの処理
            if ($dp[$turn][$y][$x][1]) {
                foreach ($b_dirs as $dir) {
                    $ny = $y + $dir[0];
                    $nx = $x + $dir[1];
                    if ($ny >= 0 && $ny < 9 && $nx >= 0 && $nx < 9) {
                        // 駒Bのまま移動（変身不可）
                        $dp[$turn + 1][$ny][$nx][1] = true;
                    }
                }
            }
        }
    }
}

// K回移動後に到達可能な位置をカウント
$count = 0;
for ($y = 0; $y < 9; $y++) {
    for ($x = 0; $x < 9; $x++) {
        if ($dp[$k][$y][$x][0] || $dp[$k][$y][$x][1]) {
            $count++;
        }
    }
}

echo $count;
