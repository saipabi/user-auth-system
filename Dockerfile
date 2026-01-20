FROM dunglas/frankenphp:latest

# Install PHP extensions
RUN apt-get update && apt-get install -y unzip zip \
    && install-php-extensions mysqli pdo_mysql mongodb redis zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy composer files first
COPY composer.json ./

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs || \
    composer require --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs \
    mongodb/mongodb:^1.15.0 predis/predis:^3.3

# Copy application files
COPY . /app

# Expose port (Railway injects PORT)
EXPOSE 8080

# 🚀 START SERVER (THIS IS THE KEY LINE)
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-8080} -t /app"]
