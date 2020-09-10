<?php
namespace _;

use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

require __DIR__ . '/../vendor/autoload.php';

$connection = connection();
$channel = $connection->channel();

$channel->basic_qos(false, 1, false);

///

$channel->queue_declare(
    'dead_queue',
    false, // $passive = false,
    false, // $durable = false,
    false, // $exclusive = false,
    true,  // $auto_delete = true,
    false, // $nowait = false,
    []
);
$channel->exchange_declare(
    'dead_exchange',
    'fanout',
    false, // $passive = false,
    false, // $durable = false,
    true,  // $auto_delete = true,
    false, // $internal = false,
    false, // $nowait = false,
    []
);
$channel->queue_bind('dead_queue', 'dead_exchange');

$channel->basic_consume('dead_queue', '', false, true, false, false, function (AMQPMessage $msg) {
    unset($msg->delivery_info['channel']);
    $properties = $msg->get_properties();
    ksort($properties);
    print_r($properties);
//    $headers = $msg->get('application_headers');
//    assert($headers instanceof AMQPTable);
//    $data = $headers->getNativeData();
//    print_r([
//        $data['x-first-death-exchange'],
//        $data['x-first-death-queue'],
//        $data['x-first-death-reason'],
//        $data['x-death'],
//    ]);
});

///

while ($channel->is_consuming()) {
    $channel->wait();
}
