<?php

use longlang\phpkafka\Consumer\ConsumeMessage;
use longlang\phpkafka\Consumer\Consumer;
use longlang\phpkafka\Consumer\ConsumerConfig;

require dirname(__DIR__) . '/vendor/autoload.php';

function consume(ConsumeMessage $message)
{
    var_dump($message->getKey() . ':' . $message->getValue());
}
$config = new ConsumerConfig();
$config->setBroker('127.0.0.1:9092');
$config->setTopic('test');
$config->setGroupId('testGroup');
$config->setClientId('test');
$config->setInterval(0.1);
$consumer = new Consumer($config, 'consume');
$consumer->start();

return;
