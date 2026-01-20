FROM dunglas/frankenphp:latest

RUN install-php-extensions mysqli pdo_mysql mongodb

COPY . /app
