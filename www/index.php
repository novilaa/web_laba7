<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Лабораторная 7 - Kafka</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .container { max-width: 600px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input, select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎓 Лабораторная работа 7 - Kafka</h1>
        <p>Система асинхронной обработки студентов</p>
        
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
                    <option value="2" selected>2 курс</option>
                    <option value="3">3 курс</option>
                    <option value="4">4 курс</option>
                </select>
            </div>
            
            <button type="submit">📨 Отправить в очередь</button>
        </form>
        
        <div style="margin-top: 30px; padding: 15px; background: #f8f9fa; border-radius: 4px;">
            <h3>📊 Статус системы:</h3>
            <p><strong>Брокер сообщений:</strong> Apache Kafka</p>
            <p><strong>PHP расширения:</strong> 
                <?php 
                echo extension_loaded('rdkafka') ? '✅ rdkafka' : '❌ rdkafka';
                ?>
            </p>
        </div>
    </div>
</body>
</html>