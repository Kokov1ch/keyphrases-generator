FROM php:8.3.7-fpm

COPY --from=composer:2.3.7 /usr/bin/composer /usr/local/bin/composer

ARG XDEBUG
RUN if [ "${XDEBUG}" = "true" ]; then \
        pecl install xdebug && \
        docker-php-ext-enable xdebug; \
    fi