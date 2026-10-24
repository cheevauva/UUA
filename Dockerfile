FROM php:8.5.12-cli-alpine

RUN apk add --no-cache nano curl bash

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

CMD ["php", "-S", "0.0.0.0:8000"]
