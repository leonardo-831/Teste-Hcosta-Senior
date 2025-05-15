FROM php:8.2-fpm

ARG UID=1000
ARG GID=1000

RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libzip-dev \
    libpq-dev \
    libssl-dev \
    libcurl4-openssl-dev \
    supervisor \
    libmagickwand-dev \
    libicu-dev \
    gnupg \
    && docker-php-ext-install pdo pdo_pgsql mbstring zip exif pcntl bcmath opcache intl

RUN pecl install mongodb \
    && echo "extension=mongodb.so" > /usr/local/etc/php/conf.d/mongodb.ini

COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

RUN groupadd -g ${GID} sammy \
    && useradd -m -u ${UID} -g ${GID} -s /bin/bash sammy

WORKDIR /var/www/html

COPY . .

COPY docker/php/fpm-pool.conf /usr/local/etc/php-fpm.d/www.conf

RUN chown -R sammy:sammy /var/www/html \
    && chmod -R 755 /var/www/html/storage

USER sammy

EXPOSE 9000

RUN composer install --no-dev --optimize-autoloader

CMD ["php-fpm"]
