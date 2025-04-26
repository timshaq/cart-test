FROM php:8.4.3-fpm

RUN apt-get update && apt-get install -y --no-install-recommends \
    libonig-dev \
    nginx \
    autoconf \
    build-essential \
    libicu-dev \
    libevent-dev \
    libssl-dev \
    git \
    curl \
    wget \
    bash \
    zlib1g-dev \
    libpng-dev \
    libjpeg-dev \
    libwebp-dev \
    libfreetype6-dev \
    zip \
    unzip \
    bzip2 \
    libzip-dev \
    librdkafka-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        intl \
        gd \
        bcmath \
        pdo_mysql \
        pcntl \
        sockets \
        zip \
        mbstring \
        exif

RUN pecl channel-update pecl.php.net \
    && pecl install -o -f \
        rdkafka \
        redis \
        event \
    && rm -rf /tmp/pear \
    && echo "extension=rdkafka.so" > /usr/local/etc/php/conf.d/rdkafka.ini \
    && echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini \
    && echo "extension=event.so" > /usr/local/etc/php/conf.d/event.ini

RUN apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/*

COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

# symfony
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash
RUN apt install symfony-cli

WORKDIR /var/www/app

#COPY ./cart /var/www/app
#COPY ./.docker/cart/etc/nginx/nginx.conf /etc/nginx/nginx.conf

RUN chown -R www-data:www-data /var/www/app

EXPOSE 8410

CMD ["sh", "-c", "php-fpm & nginx -g 'daemon off;'"]
