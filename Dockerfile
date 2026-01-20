FROM dunglas/frankenphp:latest

# Install PHP extensions
RUN apt-get update && apt-get install -y unzip zip \
    && install-php-extensions mysqli pdo_mysql mongodb redis zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy composer files first for better caching
COPY composer.json ./

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs || \
    composer require --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs \
    mongodb/mongodb:^1.15.0 predis/predis:^3.3

# Copy startup script and make it executable
COPY start.sh /app/start.sh
RUN chmod +x /app/start.sh

# Copy application files
COPY . /app

# Set document root
ENV FRANKENPHP_DOCUMENT_ROOT=/app/public

# Expose port (Railway sets PORT dynamically)
EXPOSE 8080

# Use startup script to properly handle PORT variable
CMD [\
/app/start.sh\]
