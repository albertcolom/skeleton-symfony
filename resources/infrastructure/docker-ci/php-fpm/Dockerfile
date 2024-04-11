FROM php:8.2-fpm-alpine

RUN apk --update upgrade \
    && apk add --no-cache autoconf automake make gcc g++ tzdata vim rabbitmq-c-dev librdkafka-dev linux-headers

RUN docker-php-ext-install \
        bcmath \
        opcache \
        mysqli \
        pdo \
        pdo_mysql

RUN pecl install \
        amqp \
        rdkafka \
        redis

RUN docker-php-ext-enable \
        opcache \
        redis \
        amqp \
        rdkafka

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
