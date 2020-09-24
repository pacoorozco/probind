FROM php:7.4-fpm

MAINTAINER Paco Orozco <paco@pacoorozco.info>

# Install "docker-php-extension-installer" to install PHP extensions.
ADD https://raw.githubusercontent.com/mlocati/docker-php-extension-installer/master/install-php-extensions /usr/local/bin/

# Install the PHP needed extensions
RUN chmod uga+x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions gd gmp pdo_mysql zip

# Clean up
RUN apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    && rm /var/log/lastlog /var/log/faillog

COPY ./docker/app/laravel.ini /usr/local/etc/php/conf.d

RUN usermod -u 1000 www-data

WORKDIR /var/www

CMD ["php-fpm"]

EXPOSE 9000
