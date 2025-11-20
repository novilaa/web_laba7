<?php
class Student {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function addStudent($name, $email, $course) {
        // В реальном проекте здесь была бы вставка в БД
        // Но для демонстрации просто возвращаем данные
        return [
            'id' => uniqid(),
            'name' => $name,
            'email' => $email,
            'course' => $course,
            'created_at' => date('Y-m-d H:i:s')
        ];
    }
    
    public function processStudentData($data) {
        // Симуляция обработки данных
        sleep(2); // Имитация долгой обработки
        
        $logEntry = [
            'student_id' => $data['id'],
            'name' => $data['name'],
            'email' => $data['email'],
            'course' => $data['course'],
            'processed_at' => date('Y-m-d H:i:s'),
            'status' => 'success'
        ];
        
        // Записываем в лог
        file_put_contents('processed_kafka.log', 
            json_encode($logEntry) . PHP_EOL, 
            FILE_APPEND | LOCK_EX
        );
        
        return $logEntry;
    }
}