<?php
// www/QueueManager.php

class QueueManager
{
    private const BOOTSTRAP_SERVERS = 'kafka:29092'; // именно 29092 для Confluent внутри Docker-сети
    private const TOPIC = 'lab7_topic';

    private ?\RdKafka\Producer $producer = null;

    public function __construct()
    {
        // Ничего не создаём заранее — предполагаем, что брокер позволит auto.create.topics
    }

    private function getProducer(): \RdKafka\Producer
    {
        if ($this->producer === null) {
            $this->producer = new \RdKafka\Producer();
            $this->producer->addBrokers(self::BOOTSTRAP_SERVERS);
        }
        return $this->producer;
    }

    private function getConsumer(): \RdKafka\Conf
    {
        $conf = new \RdKafka\Conf();
        $conf->set('metadata.broker.list', self::BOOTSTRAP_SERVERS);
        $conf->set('group.id', 'lab7_group');
        $conf->set('auto.offset.reset', 'earliest');
        return $conf;
    }

    public function publish(array $data): void
    {
        $producer = $this->getProducer();
        $topic = $producer->newTopic(self::TOPIC);

        $payload = json_encode($data, JSON_UNESCAPED_UNICODE);
        $topic->produce(RD_KAFKA_PARTITION_UA, 0, $payload);

        // Обработка очереди событий и ожидание доставки сообщений.
        // poll нужно вызывать, чтобы обработать delivery reports; затем делаем flush.
        $producer->poll(0);
        $attempts = 0;
        while ($producer->getOutQLen() > 0 && $attempts < 10) {
            $producer->poll(50);
            $attempts++;
        }
        // Гарантируем, что все сообщения ушли (timeout 10s)
        $producer->flush(10000);
    }

    public function consume(callable $callback): void
    {
        $conf = $this->getConsumer();
        $consumer = new \RdKafka\KafkaConsumer($conf);
        $consumer->subscribe([self::TOPIC]);

        echo "Рабочий Kafka запущен (rdkafka). Ожидание сообщений...\n";

        while (true) {
            $message = $consumer->consume(120 * 1000);

            switch ($message->err) {
                case RD_KAFKA_RESP_ERR_NO_ERROR:
                    $data = json_decode($message->payload, true);
                    $callback($data);
                    break;
                case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                case RD_KAFKA_RESP_ERR__TIMED_OUT:
                    // Нормально — просто ждём дальше
                    break;
                default:
                    throw new \Exception($message->errstr(), $message->err);
            }

            $consumer->commit($message);
        }
    }
}