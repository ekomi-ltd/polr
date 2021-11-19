# The base linux container
FROM richarvey/nginx-php-fpm:1.9.1

# Person / organization responsible for maintenance
MAINTAINER Coeus Team <aman.mukhtar@coeus-solutions.de>

# The port this docker will listen to
EXPOSE 8000

ARG APP_ENV=production
ARG UID=1000

ENV COMPOSER_VERSION 1.6.3
ENV APP_ENV ${APP_ENV}

RUN apk add --no-cache --update \
    postgresql-client \
    postgresql \
    mysql-client \
    php7 \
    php7-redis \
    php7-apcu \
    php7-bcmath \
    php7-dom \
    php7-ctype \
    php7-curl \
    php7-fpm \
    php7-fileinfo \
    php7-gd \
    php7-iconv \
    php7-intl \
    php7-json \
    php7-mbstring \
    php7-mcrypt \
    php7-mysqlnd \
    php7-opcache \
    php7-openssl \
    php7-pdo \
    php7-pdo_mysql \
    php7-pdo_pgsql \
    php7-pdo_sqlite \
    php7-phar \
    php7-posix \
    php7-session \
    php7-simplexml \
    php7-soap \
    php7-xml \
    php7-zip \
    php7-zlib \
    php7-tokenizer \
    php7-xmlwriter \
    wget sqlite git curl bash grep openssh openssl \
    supervisor

RUN touch /var/run/nginx.pid && \
    chown -R www-data:www-data /var/run/nginx.pid /etc/php7/php-fpm.d

RUN mkdir -p /var/www/html && \
    mkdir -p /usr/share/nginx/cache && \
    mkdir -p /var/cache/nginx && \
    mkdir -p /var/lib/nginx && \
    wget https://github.com/kelseyhightower/confd/releases/download/v0.16.0/confd-0.16.0-linux-amd64 -O /usr/local/bin/confd && \
    chmod u+x /usr/local/bin/confd && \
    chown -R www-data:www-data /var/www /usr/share/nginx/cache /var/cache/nginx /var/lib/nginx/ /usr/local/bin/confd /etc/nginx

# Install composer
RUN wget https://getcomposer.org/installer -O /tmp/composer-setup.php && \
    wget https://composer.github.io/installer.sig -O /tmp/composer-setup.sig && \
    php -r "if (hash('SHA384', file_get_contents('/tmp/composer-setup.php')) !== trim(file_get_contents('/tmp/composer-setup.sig'))) { unlink('/tmp/composer-setup.php'); echo 'Invalid installer' . PHP_EOL; exit(1); }" && \
    php /tmp/composer-setup.php --version=$COMPOSER_VERSION && \
    php -r "unlink('/tmp/composer-setup.php');"

WORKDIR /var/www/html/

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html && chown -R www-data:www-data /home/www-data

USER root
RUN php composer.phar global require "hirak/prestissimo:^0.3" && \
    php composer.phar install --no-dev -o && \
    rm -rf bootstrap/cache/*


USER root
RUN chmod g+rwx /var/run/nginx.pid && \
    chmod -R g+rw /var/www /usr/share/nginx/cache /var/cache/nginx /var/lib/nginx/ /etc/php7/php-fpm.d storage
