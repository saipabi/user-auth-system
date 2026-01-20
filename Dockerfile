FROM dunglas/frankenphp:latest

# Install PHP extensions
RUN install-php-extensions mysqli pdo_mysql mongodb redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy composer files first for better caching
COPY composer.json ./

# Install dependencies (ignore all platform requirements to avoid version conflicts)
RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs

# Copy application files
COPY . /app

# Set document root
ENV FRANKENPHP_DOCUMENT_ROOT=/app/public

# Expose port (Railway will set PORT env var)
EXPOSE 8080

# Start server
CMD php -S 0.0.0.0:${PORT:-8080} -t public
