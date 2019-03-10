<?php
namespace _;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Wire\AMQPTable;

require __DIR__ . '/../vendor/autoload.php';

$host = '192.168.99.100';
$port = 5672;
$user = 'ore';
$pass = 'pass';

$exchange = 'xxx';
$queue = 'xxx';

$connection = new AMQPStreamConnection($host, $port, $user, $pass);
$channel = $connection->channel();
$channel->queue_declare($queue, false, true, false, false, false, new AMQPTable(['x-max-priority' => 10]));
$channel->exchange_declare($exchange, 'direct', false, true, false, false, false);
$channel->queue_bind($queue, $exchange);

for (;;) {
    $message = $channel->basic_get($queue);
    if ($message) {
        var_dump($message->body);
        sleep(1);
        $channel->basic_ack($message->delivery_info['delivery_tag']);
    } else {
        sleep(1);
    }
}
