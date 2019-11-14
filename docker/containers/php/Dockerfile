ARG PHP_VERSION=7.3

FROM php:${PHP_VERSION}-fpm

EXPOSE 9000

WORKDIR /code/bobine

RUN usermod -u 1000 -g www-data www-data

RUN apt-get update \
    && pecl channel-update pecl.php.net

RUN docker-php-ext-install \
    bcmath \
    mysqli \
    opcache \
    pdo_mysql \
    pcntl

RUN apt-get install -y libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

RUN pecl install redis \
    && docker-php-ext-enable redis

COPY php.ini /usr/local/etc/php/
COPY fpm/www.conf /usr/local/etc/php-fpm.d/

RUN apt-get install -y curl \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get install -y supervisor

COPY supervisor/supervisord.conf /etc/supervisor/
COPY supervisor/conf.d/* /etc/supervisor/conf.d/

RUN apt-get clean

CMD ["php-fpm"]
