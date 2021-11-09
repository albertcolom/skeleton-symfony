FROM php:8.0.8-fpm-alpine

RUN apk --update upgrade \
    && apk add --no-cache autoconf automake make gcc g++ tzdata vim rabbitmq-c-dev

RUN mkdir -p /usr/src/php/ext/amqp && \
    curl -fsSL https://pecl.php.net/get/amqp | tar xvz -C "/usr/src/php/ext/amqp" --strip 1

RUN docker-php-ext-install \
        bcmath \
        opcache \
        mysqli \
        pdo \
        pdo_mysql \
        amqp

RUN pecl install redis

RUN docker-php-ext-enable \
        opcache \
        redis

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
