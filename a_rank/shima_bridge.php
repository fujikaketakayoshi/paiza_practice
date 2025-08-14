<?php
fscanf(STDIN, "%d %d", $n, $m);
$edges = [];

for ($i = 0; $i < $m; $i++) {
    fscanf(STDIN, "%d %d %d", $a, $b, $c);
    $edges[] = [$c, $a - 1, $b - 1]; // 0-indexed
}

sort($edges); // コスト順に並べる

// Union-Find 構造体
class UnionFind {
    public $parent, $rank;

    function __construct($n) {
        $this->parent = range(0, $n - 1);
        $this->rank = array_fill(0, $n, 0);
    }

    function find($x) {
        if ($this->parent[$x] !== $x) {
            $this->parent[$x] = $this->find($this->parent[$x]);
        }
        return $this->parent[$x];
    }

    function unite($x, $y) {
        $x = $this->find($x);
        $y = $this->find($y);
        if ($x === $y) return false;
        if ($this->rank[$x] < $this->rank[$y]) {
            $this->parent[$x] = $y;
        } else {
            $this->parent[$y] = $x;
            if ($this->rank[$x] === $this->rank[$y]) {
                $this->rank[$x]++;
            }
        }
        return true;
    }
}

$uf = new UnionFind($n);
$total = 0;

foreach ($edges as [$cost, $u, $v]) {
    if ($uf->unite($u, $v)) {
        $total += $cost;
    }
}

echo $total . PHP_EOL;
