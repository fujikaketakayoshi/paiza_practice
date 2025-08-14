<?php
list($h, $w) = array_map('intval', explode(' ', trim(fgets(STDIN))));

$grid = [];
for ($i = 0; $i < $h; $i++) {
    $grid[] = str_split(trim(fgets(STDIN)));
}

$dy = [-1, 0, 1, 0]; // 上右下左
$dx = [0, 1, 0, -1];

$min_string = null;
$strings = [];

function dfs($y, $x, $path, &$visited, $h, $w, $grid, $dy, $dx, &$min_string, &$strings) {
    if ($y == $h - 1 && $x == $w - 1) {
        if (is_null($min_string) || strcmp($path, $min_string) < 0) {
            $min_string = $path;
            $strings[] = $path;
        }
        return;
    }

    for ($d = 0; $d < 4; $d++) {
        $ny = $y + $dy[$d];
        $nx = $x + $dx[$d];

        if ($ny < 0 || $ny >= $h || $nx < 0 || $nx >= $w) continue;
        if ($visited[$ny][$nx]) continue;

        $visited[$ny][$nx] = true;
        dfs($ny, $nx, $path . $grid[$ny][$nx], $visited, $h, $w, $grid, $dy, $dx, $min_string, $strings);
        $visited[$ny][$nx] = false; // バックトラック
    }
}

// 初期化
$visited = array_fill(0, $h, array_fill(0, $w, false));
$visited[0][0] = true;

dfs(0, 0, $grid[0][0], $visited, $h, $w, $grid, $dy, $dx, $min_string, $strings);

echo $min_string . "\n";
sort($strings);
var_dump($strings);
