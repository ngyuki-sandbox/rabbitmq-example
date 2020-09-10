<?php
namespace _;

use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

require __DIR__ . '/../vendor/autoload.php';

$connection = connection();
$channel = $connection->channel();

$args = array_slice($argv, 1) ?: ['x'];

foreach ($args as $data) {
    $msg = new AMQPMessage($data, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
    $channel->basic_publish($msg, '', 'hello');
}

$channel->close();
$connection->close();
