FROM php:8.2-fpm

WORKDIR /var/www/html

COPY laravel-app .

RUN apt-get update && apt-get install -y mariadb-client
RUN apt-get install -y sqlite3

RUN docker-php-ext-install pdo_mysql

RUN mkdir -p /var/log/php
RUN chmod 777 -R /var/log

RUN chown -R www-data:www-data /var/www

RUN chmod -R 775 /var/www/html

RUN ls -la

COPY php.ini /usr/local/etc/php/php.ini
COPY www.conf /usr/local/etc/php-fpm.d/www.conf
COPY php-fpm.conf /usr/local/etc/php-fpm.conf

CMD [ "php-fpm", "--nodaemonize"]
EXPOSE 9000