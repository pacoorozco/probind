FROM php:7.0-apache

MAINTAINER Paco Orozco <paco@pacoorozco.info>

ENV PROBIND_ROOT /var/www/probind
ENV PROBIND_USER probind

ENV DEBIAN_FRONTEND noninteractive
ENV TERM linux

RUN apt-get update \
    && apt-get install -y --no-install-recommends --no-install-suggests \
    git \
    libmcrypt-dev \
    nodejs \
    npm \
    redis-server

RUN docker-php-ext-install \
    json \
    mbstring \
    mcrypt \
    pdo_mysql \
    zip

RUN a2enmod rewrite

RUN update-alternatives --install /usr/bin/node node /usr/bin/nodejs 10

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && npm install -g bower

COPY apache/000-default.conf /etc/apache2/sites-available/000-default.conf

RUN useradd -r -g www-data ${PROBIND_USER}

RUN git clone https://github.com/pacoorozco/probind.git ${PROBIND_ROOT} \
    && cd ${PROBIND_ROOT} \
    && composer install --no-plugins --no-scripts --prefer-dist --no-interaction \
    && bower install --allow-root 

RUN cd ${PROBIND_ROOT} \
    && cp .env.example .env \
    && sed -i "s/DB_HOST=127.0.0.1/DB_HOST=db/" .env \
    && sed -i "s/REDIS_HOST=127.0.0.1/REDIS_HOST=redis/" .env \
    && php artisan key:generate \
    && find ${PROBIND_ROOT} -type d -exec chmod 0750 {} \; \
    && find ${PROBIND_ROOT} -type f -exec chmod 0640 {} \; \
    && chown -R ${PROBIND_USER}:www-data ${PROBIND_ROOT} \
    && chown -R www-data:www-data ${PROBIND_ROOT}/bootstrap/cache \
    && chown -R www-data:www-data ${PROBIND_ROOT}/storage

WORKDIR ${PROBIND_ROOT}

EXPOSE 80

COPY scripts/setup-probind.sh /setup-probind.sh
RUN ["chmod", "+x", "/setup-probind.sh"]
