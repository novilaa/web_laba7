<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Лабораторная 7 - Kafka (Вариант 9)</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .container { max-width: 600px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input, select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .message { padding: 10px; margin: 10px 0; border-radius: 4px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎓 Добавление студента (Kafka)</h1>
        <p>Вариант 9 - Apache Kafka</p>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="message success">
                ✅ Данные студента отправлены в очередь Kafka для обработки!
            </div>
        <?php endif; ?>
        
        <form action="send.php" method="POST">
            <div class="form-group">
                <label for="name">ФИО студента:</label>
                <input type="text" id="name" name="name" required value="Иванов Иван Иванович">
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required value="ivanov@example.com">
            </div>
            
            <div class="form-group">
                <label for="course">Курс:</label>
                <select id="course" name="course" required>
                    <option value="1">1 курс</option>
                    <option value="2">2 курс</option>
                    <option value="3">3 курс</option>
                    <option value="4">4 курс</option>
                </select>
            </div>
            
            <button type="submit">📨 Отправить в очередь</button>
        </form>
        
        <div style="margin-top: 30px; padding: 15px; background: #f8f9fa; border-radius: 4px;">
            <h3>📊 Информация:</h3>
            <p><strong>Брокер сообщений:</strong> Apache Kafka</p>
            <p><strong>Топик:</strong> lab7_topic</p>
            <p><strong>Consumer Group:</strong> lab7_group</p>
            <p><strong>Лог файл:</strong> processed_kafka.log</p>
        </div>
    </div>
</body>
</html>