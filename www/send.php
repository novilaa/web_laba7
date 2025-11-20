<?php
require 'vendor/autoload.php';
require 'QueueManager.php';

$q = new QueueManager();

$q->publish([
    'action' => 'student_add',
    'name' => $_POST['name'] ?? 'Без имени',
    'timestamp' => date('Y-m-d H:i:s')
]);

echo "✅ Сообщение отправлено в Kafka!";
