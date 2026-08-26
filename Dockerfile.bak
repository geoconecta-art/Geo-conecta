# Usamos PHP 8.3 FPM
FROM php:8.3-fpm

# Instalamos dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libssl-dev \
    pkg-config

# Instalamos la extensión de MongoDB para PHP
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Instalamos extensiones básicas de Laravel
RUN docker-php-ext-install pdo_mysql bcmath

# Traemos Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . /var/www

# Permisos para Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache