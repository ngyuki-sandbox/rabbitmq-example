<?php
namespace _;

use PhpAmqpLib\Message\AMQPMessage;

require __DIR__ . '/../vendor/autoload.php';

$connection = connection();
$channel = $connection->channel();

$exchange = 'standard_exchange';
$message = new AMQPMessage("aaa");

$channel->basic_publish(
    $message,  // $msg,
    $exchange, // $exchange = '',
    '',        // $routing_key = '',
    false,     // $mandatory = false,
    false      // $immediate = false,
);
