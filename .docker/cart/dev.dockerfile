FROM php:8.4.3-fpm

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    curl \
    nginx \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl gd zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/app

COPY ./cart /var/www/app
COPY ./.docker/cart/etc/nginx/nginx.conf /etc/nginx/nginx.conf

RUN chown -R www-data:www-data /var/www/app

EXPOSE 8410

CMD ["sh", "-c", "php-fpm & nginx -g 'daemon off;'"]
