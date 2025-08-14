<?php
fscanf(STDIN, "%d", $n);

$start_positions = [];
for ($i = 0; $i < $n; $i++) {
    fscanf(STDIN, "%d %d", $x, $y);
    $start_positions[] = [$x, $y];
}

// 螺旋の動き：右→上→左→下
$dx = [1, 0, -1, 0];
$dy = [0, -1, 0, 1];

// 十分な長さの螺旋パスを生成
function generate_spiral($limit) {
    global $dx, $dy;
    $x = 0;
    $y = 0;
    $dir = 0;
    $steps = 1;
    $spiral = [[$x, $y]];
    
    while (count($spiral) <= $limit) {
        for ($i = 0; $i < 2; $i++) { // ステップを2回ずつ繰り返す
            for ($j = 0; $j < $steps; $j++) {
                $x += $dx[$dir];
                $y += $dy[$dir];
                $spiral[] = [$x, $y];
                if (count($spiral) > $limit) break 3;
            }
            $dir = ($dir + 1) % 4;
        }
        $steps++;
    }
    return $spiral;
}

$MAX_T = 50000; // 十分な時間
$spiral = generate_spiral($MAX_T);
var_dump($spiral);

$visited = []; // 座標 => true（誰かが訪れた）
$active = array_fill(0, $n, true); // 各小人がまだ動いてるか
$positions = []; // 各小人の現在位置
$stopped_count = 0;

for ($i = 0; $i < $n; $i++) {
    $positions[$i] = $start_positions[$i];
    $visited[$positions[$i][0] . "," . $positions[$i][1]] = true;
}

for ($t = 1; $t <= $MAX_T; $t++) {
    $next_positions = [];
    $position_counts = [];

    // 各小人が次に進もうとするマスを記録
    for ($i = 0; $i < $n; $i++) {
        if (!$active[$i]) continue;

        $spiral_pos = $spiral[$t]; // 螺旋のt番目
        $nx = $start_positions[$i][0] + $spiral_pos[0];
        $ny = $start_positions[$i][1] + $spiral_pos[1];
        $key = "$nx,$ny";

        $next_positions[$i] = [$nx, $ny, $key];

        if (!isset($position_counts[$key])) {
            $position_counts[$key] = 0;
        }
        $position_counts[$key]++;
    }

    // 衝突判定と移動処理
    for ($i = 0; $i < $n; $i++) {
        if (!$active[$i]) continue;

        list($nx, $ny, $key) = $next_positions[$i];

        if (isset($visited[$key]) || $position_counts[$key] >= 2) {
            $active[$i] = false;
            $stopped_count++;
        } else {
            $positions[$i] = [$nx, $ny];
            $visited[$key] = true;
        }
    }

    if ($stopped_count === $n) {
        echo ($t - 1) . PHP_EOL;
        exit;
    }
}
