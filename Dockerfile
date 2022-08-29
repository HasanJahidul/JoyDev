FROM php:8.0-apache

RUN echo "export DOCKER_ENV=true" >> /etc/apache2/envvars

ARG DB_HOST
ARG DB_PORT
ARG DB_NAME
ARG DB_USER
ARG DB_PASSWORD

RUN echo "export DB_HOST=${DB_HOST}" >> /etc/apache2/envvars
RUN echo "export DB_PORT=${DB_PORT}" >> /etc/apache2/envvars
RUN echo "export DB_NAME=${DB_NAME}" >> /etc/apache2/envvars
RUN echo "export DB_USER=${DB_USER}" >> /etc/apache2/envvars
RUN echo "export DB_PASSWORD=${DB_PASSWORD}" >> /etc/apache2/envvars

RUN docker-php-ext-install mysqli

RUN touch /usr/local/etc/php/conf.d/php.ini
RUN echo "upload_max_filesize = 1000M" >> /usr/local/etc/php/conf.d/php.ini
RUN echo "post_max_size = 1000M;" >> /usr/local/etc/php/conf.d/php.ini
RUN echo "display_errors = 1;" >> /usr/local/etc/php/conf.d/php.ini
RUN echo "display_startup_errors = 1;" >> /usr/local/etc/php/conf.d/php.ini


COPY . /var/www/html/
