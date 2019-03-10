<?php
namespace _;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

require __DIR__ . '/../vendor/autoload.php';

$host = '192.168.99.100';
$port = 5672;
$user = 'ore';
$pass = 'pass';

$exchange = 'xxx';

$connection = new AMQPStreamConnection($host, $port, $user, $pass);
$channel = $connection->channel();

$channel->basic_publish(new AMQPMessage("low",    ['priority' => 1]), $exchange);
$channel->basic_publish(new AMQPMessage("low",    ['priority' => 1]), $exchange);
$channel->basic_publish(new AMQPMessage("low",    ['priority' => 1]), $exchange);
$channel->basic_publish(new AMQPMessage("high",   ['priority' => 10]), $exchange);
