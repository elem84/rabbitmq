<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

const RABBITMQ_HOST = 'localhost';
const RABBITMQ_PORT = '5672';
const RABBITMQ_USERNAME = 'guest';
const RABBITMQ_PASSWORD = 'guest';
const RABBITMQ_QUEUE_NAME = 'moja_kolejka';

$connection = new AMQPStreamConnection(
    RABBITMQ_HOST,
    RABBITMQ_PORT,
    RABBITMQ_USERNAME,
    RABBITMQ_PASSWORD
);

$channel = $connection->channel();

$callback = function($msg) {
    echo " [x] Received: ", $msg->body, "\n";
    sleep(1);
};

$channel->basic_consume(
    RABBITMQ_QUEUE_NAME,
    '',
    false,
    true,
    false,
    false,
    $callback
);

while (count($channel->callbacks))
{
    $channel->wait();
}
