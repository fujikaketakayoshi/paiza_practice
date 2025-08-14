<?php
// 入力読み取り
$fp = fopen("php://stdin", "r");
list($H, $W) = array_map('intval', explode(' ', trim(fgets($fp))));

$grid = [];
$species_present = array_fill(0, 26, false);
for ($i = 0; $i < $H; $i++) {
    $line = trim(fgets($fp));
    $cols = preg_split('/\s+/', $line);
    $grid[$i] = $cols;
    for ($j = 0; $j < $W; $j++) {
        $c = $cols[$j];
        if ($c !== '-') {
            $species_present[ord($c) - 97] = true;
        }
    }
}

$N = intval(trim(fgets($fp)));
$adj = array_fill(0, 26, []);    // predator -> list of prey
$indegree = array_fill(0, 26, 0); // indegree = number of predators (incoming edges)
for ($k = 0; $k < $N; $k++) {
    $parts = preg_split('/\s+/', trim(fgets($fp)));
    $p = $parts[0];
    $v = $parts[1];
    $pi = ord($p) - 97;
    $vi = ord($v) - 97;
    // 重複辺を避ける（複数回の同じ入力があっても indegree を増やさない）
    if (!in_array($vi, $adj[$pi], true)) {
        $adj[$pi][] = $vi;
        $indegree[$vi]++;
        // mark present (入力保証ではA_{i,j}のいずれかだが念のため)
        $species_present[$pi] = $species_present[$pi] || true;
        $species_present[$vi] = $species_present[$vi] || true;
    }
}

// Kahn のトポロジカルソートの初期キュー（入次数0の種）
// ただし、その種が問題に登場していない（gridにもrelationsにも）場合は無視してよいが
// 入力仕様により p_k,v_k は grid のいずれかなのでほぼ問題にならない。
$queue = [];
for ($i = 0; $i < 26; $i++) {
    if ($indegree[$i] === 0 && $species_present[$i]) {
        $queue[] = $i;
    }
}

// 8方向
$dy = [-1,-1,-1,0,0,1,1,1];
$dx = [-1,0,1,-1,1,-1,0,1];

$head = 0;
while ($head < count($queue)) {
    $s = $queue[$head++]; // species index currently acting (predator)
    $sc = chr($s + 97);

    // grid 上のすべての s のマスから隣を確認して捕食を実行する
    for ($y = 0; $y < $H; $y++) {
        for ($x = 0; $x < $W; $x++) {
            if ($grid[$y][$x] !== $sc) continue; // 生きている s のマスのみ
            // 8 方向
            for ($d = 0; $d < 8; $d++) {
                $ny = $y + $dy[$d];
                $nx = $x + $dx[$d];
                if ($ny < 0 || $ny >= $H || $nx < 0 || $nx >= $W) continue;
                $target = $grid[$ny][$nx];
                if ($target === '-') continue;
                $ti = ord($target) - 97;
                // s が target を捕食できるか
                // adj[s] に ti が含まれているかを確認
                // （小さい定数なので in_array で十分）
                if (in_array($ti, $adj[$s], true)) {
                    $grid[$ny][$nx] = '-';
                    // 捕食されたマスは即時消える（以降の種の処理でも '-' なので無視される）
                }
            }
        }
    }

    // トポロジカル処理：s -> v の各辺について indegree 減らす
    foreach ($adj[$s] as $v) {
        $indegree[$v]--;
        if ($indegree[$v] === 0 && $species_present[$v]) {
            $queue[] = $v;
        }
    }
}

// 出力（マスを空白区切りで）
for ($i = 0; $i < $H; $i++) {
    echo implode(' ', $grid[$i]) . PHP_EOL;
}
