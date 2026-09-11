# Usamos PHP 8.3 FPM
FROM php:8.3-fpm

# Instalamos dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libssl-dev \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    pkg-config \
    && rm -rf /var/lib/apt/lists/*

# Instalamos la extensión de MongoDB para PHP
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Instalamos extensiones que requiere composer.json (ext-gd, ext-zip) y otras básicas
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd zip bcmath

# Traemos Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . /var/www

# Instalamos dependencias de PHP (se reinstalan si el volumen las pisa, ver docker-compose)
RUN composer install --no-interaction --optimize-autoloader

# Permisos para Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 8000

# Forma shell (no exec) para que $PORT se expanda: Railway inyecta ese puerto
# dinámicamente y no siempre es 8000. --no-reload evita que "serve" filtre
# las variables de entorno del proceso real que atiende las peticiones
# (ver docker-compose.yml para el detalle de ese bug).
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8000} --no-reload
