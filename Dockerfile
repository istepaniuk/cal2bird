FROM php:7.4

RUN apt-get update && apt-get install -y unzip libzip-dev
RUN docker-php-ext-install zip

COPY ./src /app/src
COPY ./tests /app/tests
COPY ./composer.json /app/composer.json
COPY ./composer.lock /app/composer.lock

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /app
RUN /usr/local/bin/composer install --prefer-dist --no-progress --no-suggest

COPY ./entrypoint.sh /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]
