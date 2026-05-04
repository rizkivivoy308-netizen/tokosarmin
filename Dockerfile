FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl \
    && docker-php-ext-install pdo pdo_mysql

RUN a2enmod rewrite

COPY . /var/www/html
WORKDIR /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install

RUN chown -R www-data:www-data /var/www/html

RUN cp .env.example .env || true
RUN php artisan key:generate || true

EXPOSE 80