FROM php:7.4-fpm

# Install dependencies
RUN apt-get update
RUN apt-get install -y git curl

# Install extensions
RUN docker-php-ext-install pdo_mysql mysqli
