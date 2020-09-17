FROM php:7.4-fpm

WORKDIR /var/www

RUN apt-get update && \
    apt-get install -y --no-install-recommends libzip-dev git zip unzip && \
    pecl install xdebug-2.9.2 apcu && \
    docker-php-ext-enable xdebug apcu && \
    docker-php-ext-install pdo pdo_mysql

RUN apt-get clean

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

COPY . /var/www

COPY --chown=www:www . /var/www

USER www

EXPOSE 9000

CMD ["php-fpm"]
