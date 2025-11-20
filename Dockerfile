FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git unzip curl \
    libzip-dev \
    && docker-php-ext-install zip pdo pdo_mysql

# Устанавливаем rdkafka через PECL
RUN pecl install rdkafka && docker-php-ext-enable rdkafka

WORKDIR /var/www/html

# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

# Копируем composer.json и устанавливаем зависимости
COPY composer.json ./
RUN composer install --no-dev --optimize-autoloader

# Копируем исходный код
COPY ./www /var/www/html

CMD ["php-fpm"]