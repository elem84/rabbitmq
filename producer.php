<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

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
$channel->queue_declare(
    $queue = RABBITMQ_QUEUE_NAME, // nazwa kolejki
    $passive = false,             // passive
    $durable = true,              // durable
    $exclusive = false,           // exclusive
    $auto_delete = false,         // auto deete
    $nowait = false,              // nowait
    $arguments = null,            // arguments
    $ticket = null                // ticket
);

$taskId = 0;

while (true)
{
    $taskId++;
    $messageBody = 'Zadanie #'.$taskId;
    $msg = new AMQPMessage($messageBody);

    $channel->basic_publish($msg, '', RABBITMQ_QUEUE_NAME);

    echo $messageBody . PHP_EOL;

    sleep(1);
}

