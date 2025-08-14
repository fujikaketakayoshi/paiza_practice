<?php
$s = trim(fgets(STDIN));
$l = strlen($s);

function idx($ch) {
    return ord($ch) - ord('a');
}

$stack = [];
$cur = array_fill(0, 26, 0);
$i = 0;

while ($i < $l) {
    $ch = $s[$i];

    if (ctype_digit($ch)) {
        // 数字をまとめて読む（次のトークンにかかる）
        $num = 0;
        while ($i < $l && ctype_digit($s[$i])) {
            $num = $num * 10 + intval($s[$i]);
            $i++;
        }

        if ($i < $l && $s[$i] === '(') {
            // num( の場合：新しい階層開始、num はこの '(' にかかる
            array_push($stack, [$cur, $num]);
            $cur = array_fill(0, 26, 0);
            $i++; // '(' を飛ばす
        } else {
            // num + 単一文字 の場合（例: 10h -> h を 10 回）
            if ($i < $l && ctype_lower($s[$i])) {
                $cur[idx($s[$i])] += $num;
                $i++;
            } else {
                // 不正な入力に備えて安全に進める
                $i++;
            }
        }
    } elseif ($ch === '(') {
        // 数字なしで括弧開始（倍率1）
        array_push($stack, [$cur, 1]);
        $cur = array_fill(0, 26, 0);
        $i++;
    } elseif ($ch === ')') {
        // ここで直後の数字を読まない（数字は次のトークンにかかる）
        if (!empty($stack)) {
            list($parent, $mul) = array_pop($stack);
            for ($j = 0; $j < 26; $j++) {
                // 現在階層のカウントに対して、括弧前の multiplier を掛けて親へ加算
                $parent[$j] += $cur[$j] * $mul;
            }
            $cur = $parent;
        }
        $i++;
    } elseif (ctype_lower($ch)) {
        // 単一文字（倍率1）
        $cur[idx($ch)]++;
        $i++;
    } else {
        $i++;
    }
}

// 出力（a..z を必ず出す）
for ($j = 0; $j < 26; $j++) {
    echo chr(ord('a') + $j) . ' ' . $cur[$j] . "\n";
}
