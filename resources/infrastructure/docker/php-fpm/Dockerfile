ARG ALPINE_PHP_FPM_VERSION
ARG COMPOSER_VERSION

FROM composer:${COMPOSER_VERSION} as composer
FROM php:${ALPINE_PHP_FPM_VERSION}-fpm-alpine

RUN apk --update upgrade \
    && apk add --no-cache \
    ${PHPIZE_DEPS} \
    tzdata \
    rabbitmq-c-dev \
    linux-headers

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

COPY --from=composer /usr/bin/composer /usr/bin/composer
