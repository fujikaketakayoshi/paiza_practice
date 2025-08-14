<?php
// 普通の再帰
function fib($n) {
    if ($n <= 1) return $n;
    return fib($n-1) + fib($n-2);
}

// メモ化再帰
function fibMemo($n, &$memo = []) {
    if ($n <= 1) return $n;
    if (isset($memo[$n])) return $memo[$n];
    return $memo[$n] = fibMemo($n-1, $memo) + fibMemo($n-2, $memo);
}

$n = 40;

$start = hrtime(true);
echo fib($n) . "\n";
$end = hrtime(true);
$time_ms = ($end - $start) / 1000000; // ナノ秒→ミリ秒
echo $time_ms . "msec\n";

$start = hrtime(true);
echo fibMemo($n) . "\n";
$end = hrtime(true);
$time_ms = ($end - $start) / 1000000; // ナノ秒→ミリ秒
echo $time_ms . "msec\n";

