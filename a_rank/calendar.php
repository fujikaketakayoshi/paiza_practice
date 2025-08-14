<?php
    $input_line = fgets(STDIN);
    $n = intval(rtrim($input_line));
    // var_dump($n);
    
    $line = fgets(STDIN);
    $days = array_map('intval', explode(' ', rtrim($line)));
    // var_dump($days);
    
    $holidays = [0];
    for ($i = 0; $i < $n; $i++) {
        $holidays[] = $holidays[$i] + ($days[$i] === 0 ? 1 : 0);
    }
//    var_dump($holidays);
    
    $tmp_days = array_fill(0, $n - 6, false);
    // var_dump($tmp_days);
    
    for ($i = 0; $i <= $n - 7; $i++) {
        $holiday_count = $holidays[$i + 7] - $holidays[$i];
        if ($holiday_count >= 2) {
            $tmp_days[$i] = true;
        }
    }
    
    $count = 0;
    $max_count = 0;
    foreach ($tmp_days as $d) {
        if ($d === true) {
            $count++;
            $max_count = max($max_count, $count);
        } elseif ($d === false) {
            $count = 0;
        }
    }
    
    echo $max_count > 0 ? $max_count + 6 : 0;
