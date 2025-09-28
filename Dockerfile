# Use the official PHP 8.2 FPM image as a base
FROM php:8.2-fpm

# Set the working directory
WORKDIR /var/www/html

# Install system dependencies required by Laravel
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
    libxml2-dev \
    libzip-dev

# ✅ START: Install Node.js and NPM
# ✅ ចាប់ផ្តើម៖ តម្លើង Node.js និង NPM
RUN apt-get update && \
    apt-get install -y ca-certificates curl gnupg && \
    mkdir -p /etc/apt/keyrings && \
    curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg && \
    NODE_MAJOR=20 && \
    echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_$NODE_MAJOR.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list && \
    apt-get update && \
    apt-get install nodejs -y
# ✅ END: Install Node.js and NPM

# Clear cache to keep the image size down
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install required PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Get the latest version of Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the application code into the container
COPY . .

# Set the correct permissions for storage and bootstrap cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 9000 and start the php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
