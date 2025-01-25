# Указываем базовый образ для PHP с FPM
FROM php:8.4.3-fpm AS php-base

# Устанавливаем необходимые зависимости для PHP и Composer
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl gd zip \
    && rm -rf /var/lib/apt/lists/*

# Устанавливаем Composer
COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

# Устанавливаем рабочую директорию
WORKDIR /var/www/app

# Указываем базовый образ для Nginx
FROM nginx:1.26 AS nginx-base

# Копируем конфигурацию Nginx
COPY ./.docker/cart/etc/nginx/nginx.conf /etc/nginx/nginx.conf

# Устанавливаем рабочую директорию
WORKDIR /var/www/app

# Финальный многоэтапный образ
FROM php-base AS app

# Копируем содержимое проекта
COPY ./cart /var/www/app

# Копируем Nginx в финальный образ
COPY --from=nginx-base /etc/nginx /etc/nginx

# Настраиваем права доступа
RUN chown -R www-data:www-data /var/www/app

# Открываем порты
EXPOSE 8410

# Запуск supervisord (для управления PHP и Nginx)
CMD ["sh", "-c", "php-fpm & nginx -g 'daemon off;'"]
