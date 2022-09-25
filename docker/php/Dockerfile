FROM php:8.1-fpm-bullseye

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions pdo_pgsql && docker-php-ext-enable pdo_pgsql
RUN install-php-extensions pgsql && docker-php-ext-enable pgsql
RUN install-php-extensions zip && docker-php-ext-enable zip
RUN install-php-extensions intl && docker-php-ext-enable intl
RUN install-php-extensions @composer
RUN install-php-extensions opcache && docker-php-ext-enable opcache

RUN curl -fsSL https://deb.nodesource.com/setup_17.x | bash -
RUN apt update && apt install sudo nginx supervisor nano nodejs cron wget -y

COPY ./docker/php/php.ini /usr/local/etc/php/php.ini

USER root

WORKDIR /var/www/symfony_docker

COPY --chown=www-data:www-data  /app/ /var/www/symfony_docker

#cron setup
COPY ./docker/php/crontab.local /etc/cron.d/cronjobs
RUN chmod 0644 /etc/cron.d/cronjobs && crontab /etc/cron.d/cronjobs