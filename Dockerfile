FROM php:8.2-apache

# HARD RESET Apache MPMs (guaranteed fix)
RUN rm -f /etc/apache2/mods-enabled/mpm_*.load \
 && rm -f /etc/apache2/mods-enabled/mpm_*.conf \
 && a2enmod mpm_prefork

# Install PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache rewrite
RUN a2enmod rewrite

# Configure Apache to listen on 8080 (Railway compatible)
RUN sed -i 's/80/8080/g' /etc/apache2/ports.conf \
 && sed -i 's/:80/:8080/g' /etc/apache2/sites-available/000-default.conf

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html

# Expose internal port
EXPOSE 8080

# Start Apache (MANDATORY)
CMD ["apache2-foreground"]
