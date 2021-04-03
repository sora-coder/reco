FROM php:7.2-fpm
RUN apt-get update
RUN apt-get install -y vim
RUN docker-php-ext-install pdo_mysql
COPY php.ini /usr/local/etc/php/