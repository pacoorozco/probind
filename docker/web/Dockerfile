FROM nginx:alpine

LABEL maintainer="paco@pacoorozco.info"

# Arguments defined in docker-compose.yml
ARG DOCKER_PROBIND_UID

RUN adduser --disabled-password --no-create-home --ingroup www-data --uid $DOCKER_PROBIND_UID www-data

# Remove the default conf
RUN rm /etc/nginx/conf.d/default.conf

# Set default user to www-data and configure site to use PHP-FPM
COPY ./docker/web/nginx.conf /etc/nginx/
COPY ./docker/web/sites/default.conf /etc/nginx/sites-available/default.conf

CMD ["nginx"]

EXPOSE 80
