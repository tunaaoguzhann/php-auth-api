FROM php:8.1-cli

# Gerekli PHP eklentilerini kur
RUN docker-php-ext-install pdo pdo_mysql

# Redis eklentisini kur
RUN pecl install redis && docker-php-ext-enable redis

# Composer'ı kur
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Çalışma dizinini ayarla
WORKDIR /var/www/html

# Proje dosyalarını kopyala
COPY . .

# Composer bağımlılıklarını yükle
RUN composer install --no-interaction

# Development server'ı başlat
CMD ["php", "-S", "0.0.0.0:8000", "public/index.php"]