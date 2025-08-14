<?php
    $input_line = fgets(STDIN);
    list($n, $m) = array_map('intval', explode(' ', rtrim($input_line)));
    // var_dump($n, $m);
    
    $map = [];
    for ($i = 0; $i < $n; $i++) {
        $line = fgets(STDIN);
        $map[] = str_split(rtrim($line));
    }
    // var_dump($map);
    
    $actions = [];
    for ( $i =0; $i < $m; $i++) {
        $line = fgets(STDIN);
        $actions[] = array_map('intval', explode(' ', rtrim($line)));
    }
    // var_dump($actions);
    
    $colors = ['R', 'G', 'B'];
    $dy = [-1, -1, 0, 1, 1, 1, 0, -1]; // 上、右上、右、右下、下、左下、左、左上
    $dx = [0, 1, 1, 1, 0, -1, -1, -1];
    $last_colors = [];
    
    foreach ($actions as $k => $act) {
        list($x, $y) = [$act[0] - 1, $act[1] - 1];
        $color = $colors[$k % 3];
        $last_colors[] = $color;
        $map[$y][$x] = $color;
        foreach (range(0, 7) as $dir) {
            $ny = $y + $dy[$dir];
            $nx = $x + $dx[$dir];
            $queue = new SplQueue();
            while (1) {
                if ($ny < 0 || $ny > $n - 1 || $nx < 0 || $nx > $n - 1 ) {
                    break;
                }
                if ($map[$ny][$nx] === '.') {
                    break;
                }
                if ($map[$ny][$nx] === $color) {
                    while (!$queue->isEmpty()) {
                        list($ry, $rx) = $queue->dequeue();
                        $map[$ry][$rx] = $color;
                    }
                    break;
                }
                $queue->enqueue([$ny, $nx]);
                $ny += $dy[$dir];
                $nx += $dx[$dir];
            }
        }
    }
    
    $rgb_count = ['R' => 0, 'G' => 0, 'B' => 0];
    foreach ($map as $ly) {
        foreach ($ly as $x) {
            if ($x === '.') continue;
            $rgb_count[$x]++;
        }
    }
    
    $max = max($rgb_count);
    $candidates = array_keys(array_filter($rgb_count, fn($v) => $v === $max));
    $win = '';
    if (count($candidates) === 1) {
        $win = $candidates[0];
    } else {
        // タイブレーク：最後にその色を置いた人が勝ち
        for ($i = count($last_colors) - 1; $i >= 0; $i--) {
            if (in_array($last_colors[$i], $candidates)) {
                $win = $last_colors[$i];
                break;
            }
        }
    }

    foreach ($map as $ly) {
        echo implode('', $ly) . "\n";
    }
    echo $win;
