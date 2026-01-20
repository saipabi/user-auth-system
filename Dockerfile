FROM dunglas/frankenphp:latest

# Install PHP extensions
RUN install-php-extensions mysqli pdo_mysql mongodb redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy composer files first for better caching
COPY composer.json ./

# Install dependencies (use update instead of install since no lock file exists)
# Ignore MongoDB extension version mismatch
RUN if [ -f composer.lock ]; then \
      composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-req=ext-mongodb; \
    else \
      composer update --no-dev --optimize-autoloader --no-interaction --ignore-platform-req=ext-mongodb --no-scripts; \
    fi

# Copy application files
COPY . /app

# Set document root
ENV FRANKENPHP_DOCUMENT_ROOT=/app/public

# Expose port (Railway will set PORT env var)
EXPOSE 8080

# Start server
CMD php -S 0.0.0.0:${PORT:-8080} -t public
