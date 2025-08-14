<?php
$input_line = fgets(STDIN);
$n = intval(rtrim($input_line));

// 各ビーズの次数をカウント
$degree = array_fill(1, $n, 0);
$edges = [];

// 入力を読み込み
for ($i = 0; $i < $n; $i++) {
    $line = fgets(STDIN);
    list($u, $v) = array_map('intval', explode(' ', rtrim($line)));
    
    $degree[$u]++;
    $degree[$v]++;
    $edges[] = [$u, $v];
}

// 連結性をチェック（BFS）
function isConnected($edges, $n) {
    $adj = array_fill(1, $n, []);
    foreach ($edges as $edge) {
        list($u, $v) = $edge;
        $adj[$u][] = $v;
        $adj[$v][] = $u;
    }
    
    $visited = array_fill(1, $n, false);
    $queue = [1];
    $visited[1] = true;
    $count = 1;
    
    while (!empty($queue)) {
        $current = array_shift($queue);
        foreach ($adj[$current] as $neighbor) {
            if (!$visited[$neighbor]) {
                $visited[$neighbor] = true;
                $queue[] = $neighbor;
                $count++;
            }
        }
    }
    
    return $count == $n;
}

$num_components = isConnected($edges, $n) ? 1 : 2;

// 連結グラフでのサイクル数 = 辺数 - 頂点数 + 連結成分数
$cycles = count($edges) - $n + $num_components;

// 次数の分布で判定
$degree_count = [];
for ($i = 1; $i <= $n; $i++) {
    $d = $degree[$i];
    if (!isset($degree_count[$d])) {
        $degree_count[$d] = 0;
    }
    $degree_count[$d]++;
}

// デバッグ出力（本番では削除）
// echo "Cycles: $cycles, Components: $num_components\n";
// echo "Degree count: ";
// print_r($degree_count);

// 判定
if ($num_components != 1) {
    // 連結でない
    echo "NON\n";
} else if ($cycles == 0) {
    // サイクルが0個 → 木構造 → NON
    echo "NON\n";
} else if ($cycles == 1) {
    // サイクルが1個
    if (isset($degree_count[2]) && $degree_count[2] == $n) {
        // 全てのビーズが次数2 → O型
        echo "O\n";
    } else if (isset($degree_count[2]) && isset($degree_count[1]) && isset($degree_count[3]) &&
               $degree_count[2] == $n - 2 && $degree_count[1] == 1 && $degree_count[3] == 1) {
        // 次数1が1個、次数3が1個、残りが次数2 → P型
        echo "P\n";
    } else if (isset($degree_count[2]) && isset($degree_count[4]) && isset($degree_count[1]) &&
               $degree_count[4] == 1 && $degree_count[1] == 2 && $degree_count[2] == $n - 3) {
        // 次数4が1個、次数1が2個、残りが次数2 → Q型
        echo "Q\n";
    } else {
        echo "NON\n";
    }
} else {
    // サイクルが2個以上 → NON
    echo "NON\n";
}
?>