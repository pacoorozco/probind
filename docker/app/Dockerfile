FROM php:7.0-fpm

# Update and install needed packages
RUN apt-get update \
    && apt-get install -y --no-install-recommends --no-install-suggests \
    libmcrypt-dev \
    zlib1g-dev

# Configure PHP with needed modules
RUN docker-php-ext-install \
    json \
    mbstring \
    mcrypt \
    pdo_mysql \
    zip
