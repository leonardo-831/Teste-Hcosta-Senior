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
    libmagickwand-dev \
    libicu-dev \
    gnupg \
    librabbitmq-dev \
    pkg-config \
    python3 \
    supervisor \
    && docker-php-ext-install pdo pdo_pgsql mbstring zip exif pcntl bcmath opcache intl sockets

RUN pecl install mongodb amqp \
    && docker-php-ext-enable mongodb amqp

COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

RUN groupadd -g ${GID} sammy \
    && useradd -m -u ${UID} -g ${GID} -s /bin/bash sammy

WORKDIR /var/www/html

COPY . .

COPY docker/php/fpm-pool.conf /usr/local/etc/php-fpm.d/www.conf

COPY docker/supervisor/supervisord.conf /etc/supervisor/supervisord.conf
COPY docker/supervisor/php-fpm.conf /etc/supervisor/conf.d/php-fpm.conf
COPY docker/supervisor/worker-*.conf /etc/supervisor/conf.d/

RUN mkdir -p /var/run/supervisor /var/log/supervisor \
    && chown -R sammy:sammy /var/run/supervisor /var/log/supervisor

RUN chown -R sammy:sammy /var/www/html \
    && chmod -R 755 /var/www/html/storage

USER sammy

EXPOSE 9000

RUN composer install --optimize-autoloader --no-interaction --no-progress

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
