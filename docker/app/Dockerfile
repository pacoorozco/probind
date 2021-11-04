FROM php:8.0-fpm

LABEL maintainer="paco@pacoorozco.info"

# Arguments defined in docker-compose.yml
ARG DOCKER_PROBIND_UID

# User to run Composer and Artisand Commands
ENV USER="probind"

# Install "docker-php-extension-installer" to install PHP extensions.
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Install the PHP needed extensions
RUN chmod uga+x /usr/local/bin/install-php-extensions && sync \
    && install-php-extensions gd gmp pdo_mysql xdebug zip \
    && echo "xdebug.mode=develop,coverage,debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host = host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Clean up
RUN apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    && rm /var/log/lastlog /var/log/faillog

# Copy PHP configuration for Laravel
COPY ./docker/app/laravel.ini /usr/local/etc/php/conf.d

# Create system user to run Composer and Artisan Commands
RUN useradd --groups www-data,root --uid $DOCKER_PROBIND_UID --create-home $USER

WORKDIR /var/www

USER $USER
