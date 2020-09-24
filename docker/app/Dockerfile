FROM php:7.2-fpm

MAINTAINER Paco Orozco <paco@pacoorozco.info>

# Install "zlib1g-dev".
RUN apt-get update \
    && apt-get install -y --no-install-recommends --no-install-suggests \
        zlib1g-dev \
        libgmp-dev

# Install mcrypt
RUN apt-get update -y && \
apt-get install -y libmcrypt-dev && \
pecl install mcrypt-1.0.1 && \
docker-php-ext-enable mcrypt

# Install the PHP needed extentions
RUN docker-php-ext-install \
    gmp \
    mbstring \
    pdo_mysql \
    zip

COPY ./docker/app/laravel.ini /usr/local/etc/php/conf.d

USER root

# Clean up
RUN apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    && rm /var/log/lastlog /var/log/faillog

RUN usermod -u 1000 www-data

WORKDIR /var/www

CMD ["php-fpm"]

EXPOSE 9000
