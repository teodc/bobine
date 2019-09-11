ARG PHP_VERSION=7.3

FROM php:${PHP_VERSION}-fpm

EXPOSE 9000

RUN usermod -u 1000 www-data

RUN apt-get update \
    && pecl channel-update pecl.php.net

RUN docker-php-ext-install \
    bcmath \
    mysqli \
    opcache \
    pdo_mysql \
    pcntl

#RUN apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
#    && docker-php-ext-configure gd --with-gd --with-jpeg-dir --with-png-dir --with-freetype-dir --enable-gd-native-ttf \
#    && docker-php-ext-install gd

RUN apt-get install -y libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

RUN pecl install redis \
    && docker-php-ext-enable redis

COPY php.ini /usr/local/etc/php/
COPY fpm/www.conf /usr/local/etc/php-fpm.d/

RUN apt-get install -y supervisor

COPY supervisor/supervisord.conf /etc/supervisor/
COPY supervisor/conf.d/* /etc/supervisor/conf.d/

#RUN apt-get install -y cron
#COPY cron/cron /etc/cron.d/cron
#RUN chmod 0644 /etc/cron.d/cron

RUN apt-get clean

WORKDIR /var/www/bobine

CMD ["php-fpm"]
