FROM php:7.4-apache
LABEL maintainer Nuriel Meni <nurielmeni@gmail.com>

#RUN apt-get update && apt-get install -y libxml2 libxml2-dev

# Install APCu and APC backward compatibility
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug
RUN docker-php-ext-install mysqli \
    && docker-php-ext-enable mysqli

# XDEBUG 3
COPY ./xdebug.ini /usr/local/etc/php/conf.d/

# Change the doc root
#RUN sed -i 's/web/backend\/web/g' /etc/apache2/sites-available/000-default.conf
