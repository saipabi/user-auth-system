FROM php:8.2-apache

# Install PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Disable conflicting MPM and ensure prefork is used
RUN a2dismod mpm_event || true && a2enmod mpm_prefork || true

# Configure Apache to listen on 8080 (Railway compatible)
RUN sed -i 's/80/8080/g' /etc/apache2/ports.conf \
 && sed -i 's/:80/:8080/g' /etc/apache2/sites-available/000-default.conf

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html

# Expose internal port
EXPOSE 8080

# Start Apache
CMD ["apache2-foreground"]