FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip unzip git \
  && docker-php-ext-install pdo_pgsql

RUN pecl install redis \
  && docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY composer.json ./
RUN composer install --no-dev --optimize-autoloader

WORKDIR /var/www/html

COPY src/ ./

RUN chown -R www-data:www-data /var/www