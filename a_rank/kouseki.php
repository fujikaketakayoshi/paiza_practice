<?php
// 入力を読み込む関数
function read_input() {
    // 標準入力を行単位で配列として取得し、最終行の改行を削除
    $input = explode("\n", trim(file_get_contents("php://stdin")));
    // 1行目から鉱石の数 N と 特徴の数 L を取り出す
    list($n, $l) = array_map('intval', explode(' ', array_shift($input)));
    // 残りの行を2次元配列（各鉱石の特徴の配列）に変換
    $stones = array_map(function ($line) {
        return array_map('intval', explode(' ', $line));
    }, $input);
    // N, L, 鉱石配列を返す
    return [$n, $l, $stones];
}

// 入力を受け取る
list($n, $l, $stones) = read_input();

// 覚える特徴数を 1 ～ L まで試す（少ない順に調べて、最小値を見つける）
for ($k = 1; $k <= $l; $k++) {
    // 0～L-1の特徴インデックスから k 個選ぶ全ての組み合わせを生成
    $indices = range(0, $l - 1);
    $combis = combination($indices, $k);
//    var_dump($combis);
    
    
    // 各特徴の組み合わせについて調査
    foreach ($combis as $combo) {
        $seen = []; // 組み合わせに対応する鉱石の特徴パターンを記録する連想配列

        // 全鉱石に対して、現在の特徴組み合わせで識別できるかを確認
        foreach ($stones as $stone) {
            $key = ''; // この組み合わせでの特徴値を文字列化
            foreach ($combo as $i) {
                $key .= $stone[$i]; // 特徴の値を順に連結して1つのキーにする
            }
            $seen[$key] = true; // このパターンを記録（集合として扱う）
        }
//        var_dump($seen);
        
        // 見つけたパターン数が鉱石数と一致すれば全て識別可能
        if (count($seen) === $n) {
            echo $k . "\n"; // この特徴数でOKなので出力して終了
            exit;
        }
    }
}

// 組み合わせ生成関数：配列$arrからr個選ぶすべての組み合わせを返す
function combination($arr, $r) {
    $results = [];
    dfs($arr, $r, 0, [], $results); // グローバル関数を呼び出す
    return $results;
}

// グローバル関数として定義（重複定義を避ける）
function dfs($arr, $r, $start, $combo, &$results) {
    if (count($combo) === $r) {
        $results[] = $combo;
        return;
    }
    for ($i = $start; $i < count($arr); $i++) {
        dfs($arr, $r, $i + 1, array_merge($combo, [$arr[$i]]), $results);
    }
}
