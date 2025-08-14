<?php
fscanf(STDIN, "%d %d", $H, $W);

// 入力
$grid = [];
$totalLake = 0;
for ($i = 0; $i < $H; $i++) {
    $line = trim(fgets(STDIN));
    $grid[] = str_split($line);
    $totalLake += substr_count($line, '#');
}

// 方向
$dx = [-1, 1, 0, 0];
$dy = [0, 0, -1, 1];

function countLake($grid, $H, $W, $sx, $sy)
{
    $visited = array_fill(0, $H, array_fill(0, $W, false));
    $queue = new SplQueue();
    $queue->enqueue([$sx, $sy]);
    $visited[$sx][$sy] = true;
    $count = 1;

    $dx = [-1, 1, 0, 0];
    $dy = [0, 0, -1, 1];

    while (!$queue->isEmpty()) {
        list($x, $y) = $queue->dequeue();
        for ($d = 0; $d < 4; $d++) {
            $nx = $x + $dx[$d];
            $ny = $y + $dy[$d];
            if (0 <= $nx && $nx < $H && 0 <= $ny && $ny < $W) {
                if (!$visited[$nx][$ny] && $grid[$nx][$ny] === '#') {
                    $visited[$nx][$ny] = true;
                    $queue->enqueue([$nx, $ny]);
                    $count++;
                }
            }
        }
    }

    return $count;
}

// 判定
$ans = 0;
for ($i = 0; $i < $H; $i++) {
    for ($j = 0; $j < $W; $j++) {
        if ($grid[$i][$j] === '.') {
            $ans++;
        } else {
            if ($totalLake === 1) {
                $ans++;
                continue;
            }

            // 一時的に潰す
            $grid[$i][$j] = '.';

            $found = false;
            for ($si = 0; $si < $H; $si++) {
                for ($sj = 0; $sj < $W; $sj++) {
                    if ($grid[$si][$sj] === '#') {
                        $found = true;
                        break 2;
                    }
                }
            }

            if ($found) {
                $connected = countLake($grid, $H, $W, $si, $sj);
                if ($connected === $totalLake - 1) {
                    $ans++;
                }
            }

            // 戻す
            $grid[$i][$j] = '#';
        }
    }
}

echo $ans . "\n";
