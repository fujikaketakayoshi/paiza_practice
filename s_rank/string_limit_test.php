<?php
ini_set('memory_limit', '2G');

$limit = 2 * 1024 * 1024 * 1024; // 2GB
$current = memory_get_usage();
$usable = $limit - $current - (50 * 1024 * 1024); // 50MBマージン
echo "安全に使える最大サイズ（推測）: {$usable} bytes\n";

$s = str_repeat('A', $usable);
echo "実際の長さ: " . strlen($s) . "\n";
