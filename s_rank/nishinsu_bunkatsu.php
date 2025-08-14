<?php

$c = 10000;
$k = 1;
while ($c > 0) {
    $take = min($k, $c);
    $c -= $take;
    $k <<= 1;
    echo $take . "\n";
}
