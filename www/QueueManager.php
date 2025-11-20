<?php
require_once 'vendor/autoload.php';

use Kafka\Producer;
use Kafka\ProducerConfig;
use Kafka\Consumer;
use Kafka\ConsumerConfig;

class QueueManager {
    private $topic = 'lab7_topic';
    private $brokerList = 'kafka:9092';

    public function publish($data) {
        $config = ProducerConfig::getInstance();
        $config->setMetadataBrokerList($this->brokerList);
        $config->setRequiredAck(1);

        $producer = new Producer(function() use ($data) {
            return [
                [
                    'topic' => $this->topic,
                    'value' => json_encode($data),
                    'key' => uniqid(),
                ]
            ];
        });

        $producer->send();
    }

    public function consume(callable $callback) {
        $config = ConsumerConfig::getInstance();
        $config->setMetadataBrokerList($this->brokerList);
        $config->setGroupId('lab7_group');
        $config->setTopics([$this->topic]);
        $config->setOffsetReset('earliest');

        $consumer = new Consumer();
        $consumer->start(function($topic, $part, $message) use ($callback) {
            if (isset($message['message']['value'])) {
                $data = json_decode($message['message']['value'], true);
                if ($data) {
                    $callback($data);
                }
            }
            return true;
        });
    }
}