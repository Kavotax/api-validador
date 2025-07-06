# Usa la imagen oficial PHP con FPM
FROM php:8.2-fpm

# Instala extensiones necesarias y dependencias del sistema
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl \
    libonig-dev \
    && docker-php-ext-install pdo_mysql zip mbstring

# Instala Composer globalmente
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copia los archivos del proyecto
COPY . .

# Instala dependencias PHP sin dev
RUN composer install --no-dev --optimize-autoloader

# Expone el puerto 9000 para PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
