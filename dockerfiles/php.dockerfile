FROM php:8.2-fpm

RUN docker-php-ext-install mysqli

#ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
#
#RUN chmod +x /usr/local/bin/install-php-extensions  \
#    && sync && install-php-extensions amqp  \
#    && docker-php-ext-install mysqli

WORKDIR /srv/app
