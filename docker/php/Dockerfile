FROM php:8.3.7-fpm

COPY --from=composer:2.3.7 /usr/bin/composer /usr/local/bin/composer

RUN echo "deb https://ftp.de.debian.org/debian buster main" >> /etc/apt/sources.list && \
    apt-get update && \
    apt-get install -y zip unzip

ARG XDEBUG
RUN if [ "${XDEBUG}" = "true" ]; then \
        pecl install xdebug && \
        docker-php-ext-enable xdebug; \
    fi

USER root

RUN groupadd -r app && useradd -r -g app app

RUN chown -R app:app /srv

USER app
WORKDIR /srv
COPY --chown=app:app . .

RUN composer --profile --no-scripts install
