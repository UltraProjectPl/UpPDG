FROM php:8.0-apache as api

RUN apt-get update -yqq && \
  apt-get install -yqq --no-install-recommends \
    apt-utils \
    libzip-dev zip unzip \
    git \
    libpq-dev

RUN curl -o /usr/local/bin/composer https://getcomposer.org/composer.phar && chmod +x /usr/local/bin/composer

RUN docker-php-ext-install pdo pdo_pgsql
RUN docker-php-ext-install zip

COPY vhost.conf /etc/apache2/sites-available/
RUN a2enmod rewrite
RUN a2dissite 000-default && a2ensite vhost

RUN usermod -u 1000 www-data
RUN chown www-data /var/www
