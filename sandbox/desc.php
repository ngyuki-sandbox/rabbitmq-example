<?php
namespace _;

use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

require __DIR__ . '/../vendor/autoload.php';

$connection = connection();
$channel = $connection->channel();

$channel->basic_qos(false, 1, false);

$args = new AMQPTable();
$channel->queue_declare(
    'hello_queue',
    false, // $passive = false,
    false, // $durable = false,
    false, // $exclusive = false,
    false, // $auto_delete = true,
    false, // $nowait = false,
    $args
);

$args = new AMQPTable();
$channel->queue_declare(
    'hoge_queue',
    false, // $passive = false,
    false, // $durable = false,
    false, // $exclusive = false,
    false, // $auto_delete = true,
    false, // $nowait = false,
    $args
);

$args = new AMQPTable();
$channel->queue_declare(
    'xxxx_queue',
    false, // $passive = false,
    false, // $durable = false,
    false, // $exclusive = false,
    false, // $auto_delete = true,
    false, // $nowait = false,
    $args
);
