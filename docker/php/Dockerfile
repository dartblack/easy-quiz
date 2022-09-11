FROM php:8.1-fpm-bullseye

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions pdo_pgsql && docker-php-ext-enable pdo_pgsql
RUN install-php-extensions pgsql && docker-php-ext-enable pgsql
RUN install-php-extensions redis && docker-php-ext-enable redis
RUN install-php-extensions zip && docker-php-ext-enable zip
RUN install-php-extensions intl && docker-php-ext-enable intl
RUN install-php-extensions gd && docker-php-ext-enable gd
RUN install-php-extensions ffi && docker-php-ext-enable ffi
RUN install-php-extensions vips && docker-php-ext-enable vips
RUN install-php-extensions @composer
RUN install-php-extensions opcache && docker-php-ext-enable opcache

RUN curl -fsSL https://deb.nodesource.com/setup_17.x | bash -
RUN apt update && apt install sudo nginx supervisor nano nodejs cron libvips-dev ffmpeg wget -y
RUN npm install -g npm@8.5.3

COPY ./docker/php/php.ini /usr/local/etc/php/php.ini

USER www-data:www-data

WORKDIR /var/www/symfony_docker

COPY --chown=www-data:www-data  /app/ /var/www/symfony_docker

RUN APP_ENV=prod composer install --no-interaction --optimize-autoloader --no-dev

USER root

RUN npm install && npm run build
RUN rm -rf /var/www/symfony_docker/node_modules
RUN chown -R www-data:www-data /var/www/symfony_docker/public/build

#cron setup
COPY ./docker/php/crontab.local /etc/cron.d/cronjobs
RUN chmod 0644 /etc/cron.d/cronjobs && crontab /etc/cron.d/cronjobs