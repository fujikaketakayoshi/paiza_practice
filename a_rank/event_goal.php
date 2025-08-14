<?php

list($n, $m, $g) = array_map('intval', explode(' ', trim(fgets(STDIN))));
$g--; // 0-indexed に変換

$graph = array_fill(0, $n, []);
$reverse_graph = array_fill(0, $n, []);

for ($i = 0; $i < $m; $i++) {
    list($a, $b) = array_map('intval', explode(' ', trim(fgets(STDIN))));
    $a--; $b--;
    $graph[$a][] = $b;
    $reverse_graph[$b][] = $a;
}

// -------------------------
// 1. Gを含むループがあるか
// -------------------------
function dfs_loop_check($graph, $start, $target, &$visited) {
    $stack = [$start];
    while ($stack) {
        $cur = array_pop($stack);
        if ($visited[$cur]) continue;
        $visited[$cur] = true;
        foreach ($graph[$cur] as $next) {
            if ($next === $target) return true;
            if (!$visited[$next]) $stack[] = $next;
        }
    }
    return false;
}

$visited = array_fill(0, $n, false);
$has_loop = dfs_loop_check($graph, $g, $g, $visited);

// -------------------------
// 2. 全ノードがGに到達可能か（逆グラフでDFS）
// -------------------------
function dfs_collect($graph, $start, &$visited) {
    $stack = [$start];
    while ($stack) {
        $cur = array_pop($stack);
        if ($visited[$cur]) continue;
        $visited[$cur] = true;
        foreach ($graph[$cur] as $next) {
            if (!$visited[$next]) $stack[] = $next;
        }
    }
}

$visited = array_fill(0, $n, false);
dfs_collect($reverse_graph, $g, $visited);
$all_reach_g = (count(array_filter($visited)) === $n);

// -------------------------
// 結果判定
// -------------------------
echo ($has_loop && $all_reach_g) ? 'OK' : 'NG';
