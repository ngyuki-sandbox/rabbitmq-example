<?php
namespace _;

use PhpAmqpLib\Message\AMQPMessage;

require __DIR__ . '/../vendor/autoload.php';

$connection = connection();
$channel = $connection->channel();

$exchange = 'requeue_exchange';
$message = new AMQPMessage("aaa");
$channel->basic_publish($message, $exchange);
