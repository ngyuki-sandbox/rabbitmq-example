<?php
namespace _;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

require __DIR__ . '/../vendor/autoload.php';

$exchange = 'standard_exchange';
$queue = 'standard_queue';
$consumer = 'consumer';

$connection = connection();
$channel = $connection->channel();

$channel->queue_declare(
    $queue, // $queue = '',
    false,  // $passive = false,
    true,   // $durable = false,
    false,  // $exclusive = false,
    false,  // $auto_delete = true,
    false   // $nowait = false,
);

$channel->exchange_declare(
    $exchange, // $exchange,
    'direct',  // $type,
    false,     // $passive = false,
    true,      // $durable = false,
    false,     // $auto_delete = true,
    false,     // $internal = false,
    false      // $nowait = false,
);

$channel->queue_bind(
    $queue,    // $queue,
    $exchange, // $exchange,
    '',        // $routing_key = '',
    false      // $nowait = false,
);

$callback = function (AMQPMessage $message) {
    $channel = $message->delivery_info['channel'];
    assert($channel instanceof AMQPChannel);
    $channel->basic_ack($message->delivery_info['delivery_tag']);
    print_r(array_diff_key($message->delivery_info, ['channel' => null]));
};

$channel->basic_consume(
    $queue,    // $queue = '',
    $consumer, // $consumer_tag = '',
    false,     // $no_local = false,
    false,     // $no_ack = false,
    false,     // $exclusive = false,
    false,     // $nowait = false,
    $callback  // $callback = null,
);

while (count($channel->callbacks)) {
    $channel->wait();
}
