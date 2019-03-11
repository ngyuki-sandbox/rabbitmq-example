<?php
use PhpAmqpLib\Connection\AMQPStreamConnection;

require __DIR__ . '/vendor/autoload.php';

function connection(): AMQPStreamConnection
{
    $host = '192.168.99.100';
    $port = 5672;
    $user = 'ore';
    $pass = 'pass';

    $connection = new AMQPStreamConnection($host, $port, $user, $pass);
    return $connection;
}

