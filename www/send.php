<?php
// Простая версия для тестирования
if ($_POST) {
    $data = [
        'name' => $_POST['name'],
        'email' => $_POST['email'], 
        'course' => $_POST['course'],
        'timestamp' => date('Y-m-d H:i:s')
    ];
    
    // Пока просто сохраняем в файл
    file_put_contents('submissions.log', json_encode($data) . PHP_EOL, FILE_APPEND);
    
    echo "<h1>✅ Данные получены!</h1>";
    echo "<p>Студент {$data['name']} добавлен в очередь обработки.</p>";
    echo '<a href="index.php">← Вернуться назад</a>';
}