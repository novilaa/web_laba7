FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git unzip curl librdkafka-dev \
    && rm -rf /var/lib/apt/lists/*

# Устанавливаем PECL rdkafka
RUN pecl install rdkafka && docker-php-ext-enable rdkafka

# Устанавливаем Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

# Копируем ВЕСЬ проект (включая composer.json)
COPY . /var/www/html/

# Теперь уже можно ставить зависимости на этапе сборки с игнором
RUN composer install --ignore-platform-reqs --no-dev --optimize-autoloader --no-progress --no-suggest

CMD ["php-fpm"]