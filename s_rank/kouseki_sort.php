<?php
$input_line = fgets(STDIN);
list($n, $m) = array_map('intval', explode(' ', rtrim($input_line)));

$adj = array_fill(1, $n, []);
$indegree = array_fill(1, $n, 0);

for ($i = 0; $i < $m; $i++) {
    list($p, $v) = array_map('intval', explode(' ', rtrim(fgets(STDIN))));
    if (!in_array($v, $adj[$p])) {
        $adj[$p][] = $v;
        $indegree[$v]++;
    }
}

$queue = [];
for ($i = 1; $i <= $n; $i++) {
    if ($indegree[$i] === 0) {
        $queue[] = $i;
    }
}

$result = [];
while (!empty($queue)) {
    if (count($queue) > 1) { // ← 複数候補がある＝順序が一意でない
        echo "-1\n";
        exit;
    }
    $k = array_shift($queue);
    $result[] = $k;
    foreach ($adj[$k] as $v) {
        $indegree[$v]--;
        if ($indegree[$v] === 0) {
            $queue[] = $v;
        }
    }
}

if (count($result) === $n) {
    echo implode(' ', $result) . "\n";
} else {
    echo "-1\n"; // 閉路がある場合
}
