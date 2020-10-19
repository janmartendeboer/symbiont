FROM php:7.4-cli-alpine

RUN apk add --update --no-cache icu-dev \
    && docker-php-source extract \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) intl \
    && docker-php-source delete \
    && mkdir -p /usr/local/lib/symbiont \
    && curl -L https://getcomposer.org/composer-1.phar --output /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer

WORKDIR /usr/local/lib/symbiont

COPY bin bin/
COPY lang lang/
COPY src src/
COPY composer.json .

RUN composer install --no-dev -o --no-progress && rm -f /usr/local/bin/composer

VOLUME /app
WORKDIR /app

ENTRYPOINT ["php", "/usr/local/lib/symbiont/bin/symbiont"]
