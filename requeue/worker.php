<?php
namespace _;

use PhpAmqpLib\Message\AMQPMessage;

require __DIR__ . '/../vendor/autoload.php';

$exchange = 'requeue_exchange';
$queue = 'requeue_queue';
$consumer = 'consumer';

$connection = connection();
$channel = $connection->channel();
$channel->queue_declare($queue);
$channel->exchange_declare($exchange, 'direct');
$channel->queue_bind($queue, $exchange);

$channel->basic_consume($queue, $consumer, false, false, false, false, function (AMQPMessage $message) use ($channel) {
    print_r(array_diff_key($message->delivery_info, ['channel' => null]));

    // 何回目の requeue かを知るすべはない？
    // requeue 後に遅延させたり、一定回数の requeue または一定時間で消滅させたりできない？
    // requeue し続けると同じメッセージが超繰り返し処理されるのは防ぎようがない？
    if ($message->delivery_info['redelivered']) {
        // reject で $requeue = false のときの動きは結果としては ack となにも変わらない？
        $channel->basic_reject($message->delivery_info['delivery_tag'], false);
    } else {
        $channel->basic_reject($message->delivery_info['delivery_tag'], true);
    }
});

while (count($channel->callbacks)) {
    $channel->wait();
}
