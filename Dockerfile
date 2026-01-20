FROM dunglas/frankenphp:latest

RUN install-php-extensions mysqli pdo_mysql mongodb

WORKDIR /app
COPY . /app

ENV FRANKENPHP_DOCUMENT_ROOT=/app/public
