<?php
ini_set('memory_limit', '2G'); // 2GB制限
$low = 1;
$high = 2 * 1024 * 1024 * 1024; // 2GB
$max = 0;

while ($low <= $high) {
    $mid = intdiv($low + $high, 2);
    echo "Trying $mid bytes...\n";
    try {
        $s = str_repeat('A', $mid);
        $max = $mid;
        unset($s);
        $low = $mid + 1;
    } catch (Throwable $e) {
        $high = $mid - 1;
    }
}

echo "最大長: $max bytes\n";
