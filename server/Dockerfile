FROM php:7.2-cli

RUN apt-get update \
    && apt-get install -y --no-install-recommends git zip \
    && docker-php-ext-install -j$(nproc) bcmath

WORKDIR /app

RUN curl --silent --show-error https://getcomposer.org/installer | php

COPY . /app

RUN php composer.phar install

CMD ["php", "-S", "0.0.0.0:8080", "index.php"]

EXPOSE 8080
