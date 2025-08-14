# Dockerfile

# ប្រើប្រាស់ Official PHP image ជា base
# ជ្រើសរើស version PHP ដែលត្រូវនឹង Laravel 11 របស់អ្នក (ឧ. 8.2 ឬ 8.3)
FROM php:8.3-fpm

# កំណត់ Working Directory
WORKDIR /var/www/html

# ដំឡើង Dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libonig-dev \
    libzip-dev \
    libxml2-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# ដំឡើង PHP extensions ដែល Laravel ត្រូវការ
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# ដំឡើង Composer (កម្មវិធីគ្រប់គ្រង Package របស់ PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy កូដកម្មវិធីរបស់អ្នកចូលទៅក្នុង Container
COPY . .

# កំណត់ Permission ឱ្យถูกต้อง
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 9000 និងដំណើរការ php-fpm
EXPOSE 9000
CMD ["php-fpm"]