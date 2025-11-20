<?php
require_once 'vendor/autoload.php';
require_once 'QueueManager.php';
require_once 'db.php';
require_once 'Student.php';

if ($_POST) {
    try {
        // Создаем "базу данных"
        $db = new Database();
        
        // Создаем студента
        $studentModel = new Student($db);
        $studentData = $studentModel->addStudent(
            $_POST['name'],
            $_POST['email'],
            $_POST['course']
        );
        
        // Отправляем в очередь Kafka
        $queue = new QueueManager();
        $queue->publish([
            'action' => 'add_student',
            'data' => $studentData,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
        
        // Логируем операцию
        $db->logOperation('student_added_to_queue', $studentData);
        
        // Перенаправляем с сообщением об успехе
        header('Location: index.php?success=1');
        exit;
        
    } catch (Exception $e) {
        echo "❌ Ошибка: " . $e->getMessage();
    }
}