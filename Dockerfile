FROM php:7-fpm
RUN docker-php-ext-install -j$(nproc) pdo_mysql
RUN apt-get update
RUN apt-get install -y libbz2-dev
RUN docker-php-ext-install -j$(nproc) bz2
WORKDIR /project
