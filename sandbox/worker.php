<?php
namespace _;

use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

require __DIR__ . '/../vendor/autoload.php';

$connection = connection();
$channel = $connection->channel();

$channel->basic_qos(false, 1, false);

///

$args = new AMQPTable();
$channel->queue_declare(
    'hello',
    false, // $passive = false,
    true,  // $durable = false,
    false, // $exclusive = false,
    false, // $auto_delete = true,
    false, // $nowait = false,
    $args
);

///

//$channel->basic_qos(0, 1, false);
$consumer_tag = $channel->basic_consume(
    'hello',
    '',
    false, // $no_local = false,
    true,  // $no_ack = false,
    false, // $exclusive = false,
    false, // $nowait = false,
    function (AMQPMessage $msg) use ($channel) {
        var_dump($msg->body);
//        sleep(3);
//        $channel->basic_ack($msg->getDeliveryTag());
    }
);

$term = false;
$sig_handler = function($signo) use (&$term) {
    var_dump("signo:$signo");
    $term = true;
};

pcntl_signal(SIGTERM, $sig_handler);
pcntl_signal(SIGHUP, $sig_handler);
pcntl_signal(SIGINT, $sig_handler);
pcntl_async_signals(true);

while (!$term && $channel->is_consuming()) {
    $channel->wait();
}

var_dump("exit");
