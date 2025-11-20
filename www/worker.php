<?php
// www/worker.php

// Поддерживаем автозагрузчик как в `www/vendor` так и в корне проекта `../vendor`.
$autoload = null;
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    $autoload = __DIR__ . '/vendor/autoload.php';
} elseif (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    $autoload = __DIR__ . '/../vendor/autoload.php';
}
if ($autoload) {
    require $autoload;
} else {
    // Не нашли автозагрузчик — попытаемся подключить вручную (fallback)
    require __DIR__ . '/QueueManager.php';
    require __DIR__ . '/students.php';
}

require __DIR__ . '/QueueManager.php';
require __DIR__ . '/students.php';

echo "Рабочий Kafka запущен и ждёт сообщений...\n";

$pdo = require __DIR__ . '/db.php';               // ← теперь возвращает PDO!
$student = new Student($pdo);

$queue = new QueueManager();

$queue->consume(function ($data) use ($student) {
    echo "Получено сообщение: " . json_encode($data, JSON_UNESCAPED_UNICODE) . PHP_EOL;

    if (isset($data['action']) && $data['action'] === 'add_student') {
        $student->add($data['name']);
        echo "Студент «{$data['name']}» добавлен в БД\n";
    }

    // логируем всё
    file_put_contents('processed_kafka.log', json_encode($data, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND | LOCK_EX);
});