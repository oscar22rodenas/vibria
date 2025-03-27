FROM wordpress:latest

# Instalar Composer
RUN apt-get update && apt-get install -y curl unzip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && apt-get clean

# Copiar el archivo composer.json para instalar plugins
COPY Blog/wordpress/composer.json /var/www/html/composer.json

# Instalar los plugins con Composer
RUN composer install --no-dev --optimize-autoloader

# Establecer permisos correctos
RUN chown -R www-data:www-data /var/www/html/wp-content/plugins
