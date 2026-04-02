ARG PHP_VERSION=8.3

FROM composer:2 AS composer

FROM php:${PHP_VERSION}-cli

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        bash \
        git \
        unzip \
        ca-certificates \
        $PHPIZE_DEPS \
        pkg-config \
        libicu-dev \
        libsqlite3-dev \
        libonig-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install -j"$(nproc)" intl pdo_sqlite mbstring

COPY --from=composer /usr/bin/composer /usr/local/bin/composer

WORKDIR /workspace
