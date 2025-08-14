<?php
list($H, $W) = array_map('intval', explode(' ', trim(fgets(STDIN))));
$map = [];
for ($i = 0; $i < $H; $i++) {
    $line = str_split(trim(fgets(STDIN)));
    $map[] = $line;
    foreach ($line as $j => $cell) {
        if ($cell === 'S') {
            $sy = $i;
            $sx = $j;
        }
    }
}

$dirs = [
    'U' => [-1, 0],
    'D' => [1, 0],
    'L' => [0, -1],
    'R' => [0, 1],
];

$visited = array_fill(0, $H, array_fill(0, $W, false));
$answer = null;

function dfs($y, $x, $path) {
    global $map, $dirs, $H, $W, $visited, $answer;

    if ($map[$y][$x] === 'G') {
        $answer = $path;
        return true;  // 探索終了の合図
    }

    $visited[$y][$x] = true;

    foreach ($dirs as $dir => [$dy, $dx]) {
        $ny = $y + $dy;
        $nx = $x + $dx;

        if ($ny < 0 || $ny >= $H || $nx < 0 || $nx >= $W) continue;
        if ($visited[$ny][$nx]) continue;
        if ($map[$ny][$nx] === '#') continue;

        if (dfs($ny, $nx, $path . $dir)) return true;  // 成功したら中断
    }

    $visited[$y][$x] = false;  // バックトラック
    return false;
}

dfs($sy, $sx, '');
echo $answer . "\n";
