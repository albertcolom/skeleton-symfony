FROM php:8.2-fpm-alpine

ENV PHP_IDE_CONFIG 'serverName=DockerApp'

RUN apk --update upgrade \
    && apk add --no-cache autoconf automake make gcc g++ tzdata vim rabbitmq-c-dev linux-headers

RUN docker-php-ext-install \
        bcmath \
        opcache \
        mysqli \
        pdo \
        pdo_mysql

RUN pecl install \
        amqp \
        redis \
        xdebug

RUN docker-php-ext-enable \
        opcache \
        xdebug \
        redis \
        xdebug \
        amqp

COPY xdebug.ini /usr/local/etc/php/conf.d/

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
