<?php
// В реальном проекте здесь было бы подключение к БД
// Для демонстрации используем файл как "базу данных"
class Database {
    public function connect() {
        return $this;
    }
    
    public function logOperation($operation, $data) {
        $log = [
            'operation' => $operation,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        file_put_contents('database.log', 
            json_encode($log) . PHP_EOL, 
            FILE_APPEND | LOCK_EX
        );
    }
}