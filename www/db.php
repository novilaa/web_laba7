<?php
// www/db.php

// Попробуем подключить автозагрузчик Composer — сначала в `www/vendor`, затем в корне проекта `../vendor`.
$autoloadPaths = [
    __DIR__ . '/vendor/autoload.php',
    __DIR__ . '/../vendor/autoload.php',
];
$found = false;
foreach ($autoloadPaths as $p) {
    if (file_exists($p)) {
        require_once $p;
        $found = true;
        break;
    }
}
if (! $found) {
    throw new \RuntimeException("Composer autoload not found. Run 'composer install' in project root or in 'www'.");
}

use Dotenv\Dotenv;

// Загружаем .env, если он есть
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad();
} elseif (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->safeLoad();
}

// Используем SQLite — самый простой и надёжный вариант для лабы
$dbFile = __DIR__ . '/database.sqlite';

$pdo = new PDO("sqlite:$dbFile");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Создаём таблицу, если её нет
$pdo->exec("CREATE TABLE IF NOT EXISTS students (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

return $pdo; // ← ЭТО ОБЯЗАТЕЛЬНО!