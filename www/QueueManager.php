<?php

class QueueManager {
    private $topic = 'lab7_topic';
    private $brokerList = 'kafka:9092';

    public function publish($data) {
        try {
            $conf = new RdKafka\Conf();
            $conf->set('bootstrap.servers', $this->brokerList);
            
            $producer = new RdKafka\Producer($conf);
            $topic = $producer->newTopic($this->topic);
            
            $topic->produce(RD_KAFKA_PARTITION_UA, 0, json_encode($data));
            $producer->poll(0);
            
            // Ожидаем отправки
            for ($flushRetries = 0; $flushRetries < 10; $flushRetries++) {
                $result = $producer->flush(10000);
                if (RD_KAFKA_RESP_ERR_NO_ERROR === $result) {
                    break;
                }
            }
            
        } catch (Exception $e) {
            error_log("Kafka publish error: " . $e->getMessage());
            throw $e;
        }
    }

    public function consume(callable $callback) {
        $conf = new RdKafka\Conf();
        $conf->set('group.id', 'lab7_group');
        $conf->set('bootstrap.servers', $this->brokerList);
        $conf->set('auto.offset.reset', 'earliest');

        $consumer = new RdKafka\KafkaConsumer($conf);
        $consumer->subscribe([$this->topic]);

        echo "👷 Consumer started for topic: {$this->topic}\n";

        while (true) {
            $message = $consumer->consume(120*1000);
            
            switch ($message->err) {
                case RD_KAFKA_RESP_ERR_NO_ERROR:
                    $data = json_decode($message->payload, true);
                    if ($data) {
                        $callback($data);
                    }
                    break;
                case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                    echo "No more messages\n";
                    break;
                case RD_KAFKA_RESP_ERR__TIMED_OUT:
                    echo "Timed out\n";
                    break;
                default:
                    throw new Exception($message->errstr(), $message->err);
            }
        }
    }
}