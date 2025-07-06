# Etapa 1: Build PHP con extensiones y Composer
FROM php:8.2-fpm

# Instala dependencias del sistema y extensiones PHP necesarias
RUN apt-get update && apt-get install -y \
    nginx \
    libzip-dev zip unzip git curl libonig-dev \
    && docker-php-ext-install pdo_mysql zip mbstring

# Instala Composer globalmente
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copia archivos del proyecto
COPY . .

# Instala dependencias PHP
RUN composer install --no-dev --optimize-autoloader

# Copia configuración Nginx personalizada
COPY ./nginx.conf /etc/nginx/sites-available/default

# Expone puerto 80
EXPOSE 80

# Script para iniciar PHP-FPM y Nginx
CMD service nginx start && php-fpm
