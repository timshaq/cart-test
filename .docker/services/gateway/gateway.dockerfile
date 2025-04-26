FROM php:8.4.3-fpm

RUN apt-get update && apt-get install -y --no-install-recommends \
    nginx \
    libssl-dev \
    bash

RUN apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/*

WORKDIR /var/www/app

RUN chown -R www-data:www-data /var/www/app

EXPOSE 8410

CMD ["sh", "-c", "php-fpm & nginx -g 'daemon off;'"]
