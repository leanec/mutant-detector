FROM php:7.4-fpm

# Install dependencies
RUN apt-get update
RUN apt-get install -y curl unzip

# Install extensions
RUN docker-php-ext-install pdo_mysql
RUN pecl install xdebug-2.9.8 && docker-php-ext-enable xdebug
RUN pecl install -o -f redis \
&&  rm -rf /tmp/pear \
&&  docker-php-ext-enable redis

# Install composer
RUN apt-get install -y mc
RUN curl -L -C - --progress-bar -o /usr/local/bin/composer https://getcomposer.org/composer.phar
RUN chmod 755 /usr/local/bin/composer