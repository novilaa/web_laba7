<?php
// Подключаем автозагрузчик (ищем и в `www/vendor`, и в корне проекта `../vendor`).
$autoload = null;
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    $autoload = __DIR__ . '/vendor/autoload.php';
} elseif (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    $autoload = __DIR__ . '/../vendor/autoload.php';
}
if ($autoload) {
    require $autoload;
} else {
    // Если автозагрузчик не найден, подключаем только локальные файлы (чтобы показать понятную ошибку позже)
    // Но лучше запустить `composer install`.
}
require __DIR__ . '/QueueManager.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    if ($name !== '') {
        $queue = new QueueManager();
        $queue->publish([
            'action' => 'add_student',
            'name'   => $name,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
        echo "✅ Сообщение отправлено в Kafka! Скоро студент будет добавлен.";
    } else {
        echo "Ошибка: имя пустое!";
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Лаба 7 — Kafka</title>
</head>
<body>
    <h1>Асинхронное добавление студента (Kafka)</h1>
    <form method="post">
        <input type="text" name="name" placeholder="Имя студента" required style="width:300px;padding:8px;">
        <button type="submit" style="padding:8px 16px;">Отправить в очередь</button>
    </form>

    <hr>
    <p><a href="students.php">Посмотреть всех студентов в БД</a></p>
    <p><a href="processed_kafka.log" target="_blank">Лог обработанных сообщений</a></p>
</body>
</html>