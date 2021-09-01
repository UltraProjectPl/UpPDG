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

RUN docker-php-ext-install bcmath

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && sync && install-php-extensions http

RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

RUN echo " \n\
xdebug.mode=develop,debug \n\
xdebug.client_host=host.docker.internal \n\
xdebug.discover_client_host=1 \n\
xdebug.idekey=PHPSTORM \n\
" >> /usr/local/etc/php/php.ini

COPY vhost.conf /etc/apache2/sites-available/

RUN a2enmod rewrite
RUN a2dissite 000-default && a2ensite vhost

RUN usermod -u 1000 www-data
RUN chown www-data /var/www

FROM api as worker

COPY supervisord.conf /etc/supervisor.d/messenger.ini

CMD ["/usr/bin/supervisord"]

FROM api as scheduler

COPY cron/crontab /etc/crontabs/www-data

CMD ["/usr/sbin/crond", "-f", "-L", "/dev/stdout"]
