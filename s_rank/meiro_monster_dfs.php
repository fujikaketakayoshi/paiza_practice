<?php
$input_line = fgets(STDIN);
list($h ,$w) = array_map('intval', explode(' ', rtrim($input_line)));

$grid = [];
$start = [];
$goal = [];
for ($i = 0; $i < $h; $i++) {
    $line = str_split(rtrim(fgets(STDIN)));
    foreach ($line as $j => &$val) {
        if ($val === 'S') {
            $start = [$i, $j];
            $val = 0;
        } elseif ($val === 'G') {
            $goal = [$i, $j];
            $val = 0;
        } else {
            $val = intval($val);
        }
    }
    $grid[] = $line;
}

$dy = [-1, 0, 1, 0]; // 上右下左
$dx = [0, 1, 0, -1]; // 上右下左
$visited = array_fill(0, $h, array_fill(0, $w, false));
$min_monster_num = PHP_INT_MAX;

function dfs($cy, $cx, $monster_num, &$visited, &$min_monster_num) {
    global $dy, $dx, $goal, $h, $w, $grid;
    
    // 枝刈り
    if ($monster_num >= $min_monster_num) return;

    // ゴール到達
    if ($cy === $goal[0] && $cx === $goal[1]) {
        $min_monster_num = min($min_monster_num, $monster_num);
        return;
    }

    foreach (range(0, 3) as $dir) {
        $ny = $cy + $dy[$dir];
        $nx = $cx + $dx[$dir];
        if ($ny < 0 || $ny >= $h || $nx < 0 || $nx >= $w) continue;
        if ($visited[$ny][$nx]) continue;

        $visited[$ny][$nx] = true;
        dfs($ny, $nx, $monster_num + $grid[$ny][$nx], $visited, $min_monster_num);
        $visited[$ny][$nx] = false; // バックトラック
    }
}

$visited[$start[0]][$start[1]] = true;
dfs($start[0], $start[1], 0, $visited, $min_monster_num);

echo $min_monster_num, "\n";
