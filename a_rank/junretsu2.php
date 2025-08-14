<?php
    $n = 20;
	$a = 3;
	$b = 5;
    
    $steps = array_fill(0, $n + 1, false);
    // var_dump($steps);
    
    function generate($target, $current = [], $sum = 0) {
        global $a, $b;
        $results = [];
        
        if ($sum >= $target) {
            return [];
        }
        
        if (!empty($current)) {
            $results[] = $current;
        }
        
        $results = array_merge($results, generate($target, array_merge($current, [$a]), $sum + $a));
        $results = array_merge($results, generate($target, array_merge($current, [$b]), $sum + $b));
        
        return $results;
    }
    
    $junretu = generate($n);
    var_dump($junretu);