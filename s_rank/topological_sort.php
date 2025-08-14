<?php
$N = intval(trim(fgets(STDIN)));
$adj = [];       // 作業 -> 次に行う作業のリスト
$indegree = [];  // 作業 -> 先に終えるべき作業の数
$tasks = [];     // 登場した作業一覧

for ($i = 0; $i < $N; $i++) {
    [$p, $v] = explode(' ', trim(fgets(STDIN)));
    // 初期化
    if (!isset($adj[$p])) $adj[$p] = [];
    if (!isset($adj[$v])) $adj[$v] = [];
    if (!isset($indegree[$p])) $indegree[$p] = 0;
    if (!isset($indegree[$v])) $indegree[$v] = 0;

    // 重複辺を防ぐ
    if (!in_array($v, $adj[$p], true)) {
        $adj[$p][] = $v;
        $indegree[$v]++;
    }
    $tasks[$p] = true;
    $tasks[$v] = true;
}

// 初期キュー（依存なし作業）
$queue = [];
foreach ($tasks as $task => $_) {
    if ($indegree[$task] === 0) {
        $queue[] = $task;
    }
}

$result = [];
$head = 0;
while ($head < count($queue)) {
    $s = $queue[$head++];
    $result[] = $s;

    foreach ($adj[$s] as $v) {
        $indegree[$v]--;
        if ($indegree[$v] === 0) {
            $queue[] = $v;
        }
    }
}

// 全作業を処理できたか確認
if (count($result) === count($tasks)) {
    echo implode(' ', $result) . PHP_EOL;
} else {
    echo "Impossible\n";
}
