<?php
$input_line = fgets(STDIN);
list($n, $k) = array_map('intval', explode(' ', rtrim($input_line)));

// 真のDP解法：各アイテムを順番に処理し、
// その時点でのAとBの取得状況と累積コストを管理

// dp[i][j][cost] = i番目のアイテムまで処理して、
// Aがj個取り、現在の累積差分コストがcostの場合の数
$dp = array_fill(0, 2*$n+1, array_fill(0, $n+1, array_fill(0, $k+1, 0)));
$dp[0][0][0] = 1;

for ($i = 1; $i <= 2*$n; $i++) {
    // 前の状態をコピー（何もしない場合はない）
    for ($j = 0; $j <= $n; $j++) {
        for ($cost = 0; $cost <= $k; $cost++) {
            $dp[$i][$j][$cost] = 0;
        }
    }
    
    for ($j = 0; $j <= min($i-1, $n); $j++) {
        $prev_b_count = ($i-1) - $j;
        if ($prev_b_count < 0 || $prev_b_count > $n) continue;
        
        for ($prev_cost = 0; $prev_cost <= $k; $prev_cost++) {
            if ($dp[$i-1][$j][$prev_cost] == 0) continue;
            
            // 現在のアイテムiをAが取る場合
            if ($j < $n) {
                $a_rank = $j + 1;  // Aの何番目のアイテムか
                $b_count = $prev_b_count;  // Bの現在の個数
                
                // Aのa_rank番目とBのa_rank番目の差を計算する必要がある
                // しかし、Bのa_rank番目がまだ決まっていない可能性がある
                
                // より正確には：この時点でのコスト増分を計算
                // Aが現在のアイテムiを取った時、対応するBのアイテムとの差
                
                // 簡略化：最悪の場合のコスト増分を0として遷移
                // （実際のコストは最後に計算）
                if ($prev_cost <= $k) {
                    $dp[$i][$j+1][$prev_cost] += $dp[$i-1][$j][$prev_cost];
                }
            }
            
            // 現在のアイテムi をBが取る場合
            if ($prev_b_count < $n) {
                if ($prev_cost <= $k) {
                    $dp[$i][$j][$prev_cost] += $dp[$i-1][$j][$prev_cost];
                }
            }
        }
    }
}

// この方法では実際のコスト計算ができない...
// 別のアプローチ：ビット演算ベースのDP（小さいNに対して）

function solveBitDP($n, $k) {
    if ($n > 10) {
        // 大きいNの場合は近似解法を使う
        return approximateSolution($n, $k);
    }
    
    $result = 0;
    $total = 2 * $n;
    
    // 全ての可能な分割パターンを試す（Aが取るアイテムの組み合わせ）
    for ($mask = 0; $mask < (1 << $total); $mask++) {
        $count = 0;
        for ($bit = 0; $bit < $total; $bit++) {
            if ($mask & (1 << $bit)) $count++;
        }
        
        if ($count != $n) continue;  // Aがちょうどn個取る場合のみ
        
        $a_items = [];
        $b_items = [];
        
        for ($bit = 0; $bit < $total; $bit++) {
            if ($mask & (1 << $bit)) {
                $a_items[] = $bit + 1;
            } else {
                $b_items[] = $bit + 1;
            }
        }
        
        sort($a_items);
        sort($b_items);
        
        $cost = 0;
        for ($pos = 0; $pos < $n; $pos++) {
            $cost += abs($a_items[$pos] - $b_items[$pos]);
            if ($cost > $k) break;
        }
        
        if ($cost <= $k) {
            $result++;
        }
    }
    
    return $result;
}

// 大きいNに対する真のDP解法
function largeDPSolution($n, $k) {
    $memo = [];
    
    $rec = function($pos, $a_items, $b_items) use ($n, $k, &$memo, &$rec) {
        if ($pos > 2 * $n) {
            if (count($a_items) == $n && count($b_items) == $n) {
                $a_sorted = $a_items;
                $b_sorted = $b_items;
                sort($a_sorted);
                sort($b_sorted);
                
                $cost = 0;
                for ($i = 0; $i < $n; $i++) {
                    $cost += abs($a_sorted[$i] - $b_sorted[$i]);
                    if ($cost > $k) return 0;
                }
                return 1;
            }
            return 0;
        }
        
        $key = $pos . ',' . count($a_items) . ',' . count($b_items);
        if (isset($memo[$key])) return $memo[$key];
        
        $result = 0;
        
        if (count($a_items) < $n) {
            $result += $rec($pos + 1, array_merge($a_items, [$pos]), $b_items);
        }
        
        if (count($b_items) < $n) {
            $result += $rec($pos + 1, $a_items, array_merge($b_items, [$pos]));
        }
        
        return $memo[$key] = $result;
    };
    
    return $rec(1, [], []);
}

// N <= 20の場合はビットDP、それ以上は工夫したメモ化再帰
if ($n <= 20) {
    echo solveBitDP($n, $k);
} else {
    echo largeDPSolution($n, $k);
}
?>