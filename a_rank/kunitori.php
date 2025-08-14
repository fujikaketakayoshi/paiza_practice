<?php
$input_line = fgets(STDIN);
list($n, $k, $q) = array_map('intval', explode(' ', rtrim($input_line)));

$kss = [];
for ($i = 0; $i < $k; $i++) {
    $line = fgets(STDIN);
    $kss[] = array_map('intval', explode(' ', rtrim($line)));
}

$qss = [];
for ($i = 0; $i < $q; $i++) {
    $line = fgets(STDIN);
    $qss[] = intval(rtrim($line));
}

class UnionFind {
    public $parent;

    function __construct($n) {
        $this->parent = range(0, $n - 1);
    }

    function find($x) {
        if ($this->parent[$x] !== $x) {
            $this->parent[$x] = $this->find($this->parent[$x]); // 経路圧縮
        }
        return $this->parent[$x];
    }

    function unite($x, $y) {
        $rx = $this->find($x);
        $ry = $this->find($y);
        if ($rx !== $ry) {
            $this->parent[$ry] = $rx;
        }
    }
}

$uf = new UnionFind($n);

// 戦闘処理
foreach ($kss as [$x, $y]) {
    $uf->unite($x, $y);
}

// クエリ出力
foreach ($qss as $e) {
    echo $uf->find($e) . "\n";
}
