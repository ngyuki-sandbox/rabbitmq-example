<?php
use PhpAmqpLib\Connection\AMQPStreamConnection;

require __DIR__ . '/vendor/autoload.php';

function connection(): AMQPStreamConnection
{
    $host = '127.0.0.1';
    $port = 5672;
    $user = 'ore';
    $pass = 'pass';

    $connection = new AMQPStreamConnection($host, $port, $user, $pass);
    return $connection;
}

