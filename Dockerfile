FROM dunglas/frankenphp:latest

# 1. Added zip and unzip here so Composer can extract files
RUN apt-get update && apt-get install -y unzip zip \
    && install-php-extensions mysqli pdo_mysql mongodb redis zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy composer files first for better caching
COPY composer.json ./

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs

# Copy application files
COPY . /app

# Set document root
ENV FRANKENPHP_DOCUMENT_ROOT=/app/public

# Expose port
EXPOSE 8080

# Start server
CMD php -S 0.0.0.0:${PORT:-8080} -t public