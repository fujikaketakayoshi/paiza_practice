<?php
$pq = new SplPriorityQueue();
$pq->setExtractFlags(SplPriorityQueue::EXTR_BOTH); // data と priority 両方取り出す
$pq->insert('A', 5);
$pq->insert('B', 1);
$pq->insert('C', 8);

while (!$pq->isEmpty()) {
    $item = $pq->extract(); // ['data' => ..., 'priority' => ...]
    echo $item['data'], ' ', $item['priority'], PHP_EOL;
}
// 出力順: C 8 / A 5 / B 1
