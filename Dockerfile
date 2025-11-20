FROM php:8.2-fpm

# Используем более надежные зеркала и уменьшаем количество пакетов
RUN apt-get update && apt-get install -y \
    curl \
    libzip-dev \
    && docker-php-ext-install zip pdo pdo_mysql

# Устанавливаем rdkafka через PECL
RUN pecl install rdkafka && docker-php-ext-enable rdkafka

WORKDIR /var/www/html

# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Копируем исходный код
COPY ./www /var/www/html

# Копируем composer.json и устанавливаем зависимости
COPY composer.json ./
RUN composer install --no-dev --optimize-autoloader

CMD ["php-fpm"]