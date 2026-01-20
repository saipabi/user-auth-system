FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    unzip zip git \
    && docker-php-ext-install mysqli pdo pdo_mysql

WORKDIR /app

COPY . /app

EXPOSE 8080

CMD sh -c "php -S 0.0.0.0:${PORT:-8080} -t /app"
