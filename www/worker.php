<?php
require_once 'vendor/autoload.php';
require_once 'QueueManager.php';
require_once 'db.php';
require_once 'Student.php';

echo "🎯 Worker для Kafka запущен...\n";
echo "📝 Ожидание сообщений из топика lab7_topic\n";
echo "⏹️  Для остановки нажмите Ctrl+C\n\n";

try {
    $db = new Database();
    $studentModel = new Student($db);
    $queue = new QueueManager();
    
    $queue->consume(function($data) use ($studentModel, $db) {
        echo "📥 Получено сообщение: " . date('Y-m-d H:i:s') . "\n";
        echo "📋 Действие: " . ($data['action'] ?? 'unknown') . "\n";
        
        if (isset($data['action']) && $data['action'] === 'add_student') {
            // Обрабатываем данные студента
            $result = $studentModel->processStudentData($data['data']);
            
            echo "✅ Обработан студент: " . $result['name'] . "\n";
            echo "📧 Email: " . $result['email'] . "\n";
            echo "🎓 Курс: " . $result['course'] . "\n";
            echo "⏰ Время обработки: " . $result['processed_at'] . "\n";
            echo "---\n";
            
            // Логируем успешную обработку
            $db->logOperation('student_processed', $result);
        }
        
        echo "\n";
    });
    
} catch (Exception $e) {
    echo "❌ Ошибка в worker: " . $e->getMessage() . "\n";
    sleep(5); // Пауза перед перезапуском
}