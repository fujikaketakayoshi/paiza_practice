<?php
    $input_line = fgets(STDIN);
    list($h, $w) = explode(' ', rtrim($input_line));
    
    $map = [];
    while ($line = fgets(STDIN)) {
        $map[] = str_split(rtrim($line));
    }
    
    for ($i = 0; $i < $h; $i++) {
        for ($j = 0; $j < $w; $j++) {
            if ($map[$i][$j] == 'S') {
                $sy = $i;
                $sx = $j;
                break 2;
            }
        }
    }
//    $map[$sy][$sx] = '.';
    
    
    $visited = array_fill(0, $h, array_fill(0, $w, false));
    $visited[$sy][$sx] = true;
    
    
    $dy = [-1, 1, 0, 0];
    $dx = [0, 0, -1, 1];
    
    $queue = new SplQueue();
    $queue->enqueue([$sy, $sx]);
    while (!$queue->isEmpty()) {
        list($y, $x) = $queue->dequeue();
        foreach (range(0, 3) as $dir) {
            $ny = $y + $dy[$dir];
            $nx = $x + $dx[$dir];
            
            if ( $ny < 0 || $ny > $h - 1 || $nx < 0 || $nx > $w - 1) {
                echo 'YES';
                exit;
            }
            
            if (!$visited[$ny][$nx] && $map[$ny][$nx] == '.') {
                $queue->enqueue([$ny, $nx]);
                $visited[$ny][$nx] = true;
            }
        }
    }
    echo 'NO';