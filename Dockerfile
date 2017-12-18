FROM php:7-fpm
RUN docker-php-ext-install -j$(nproc) pdo_mysql
WORKDIR /project
RUN curl --silent --show-error https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer && \
    chmod a+x /usr/local/bin/composer
