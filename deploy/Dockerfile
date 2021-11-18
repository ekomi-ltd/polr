# The base linux container
FROM richarvey/nginx-php-fpm:1.8.2

# Person / organization responsible for maintenance
MAINTAINER Coeus Team <aman.mukhtar@coeus-solutions.de>

# The port this docker will listen to
EXPOSE 8000

#Environment variables
ENV RUN_SCRIPTS=1
ENV SKIP_CHOWN=1
ENV SKIP_COMPOSER=1

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

# Set current working directory for host commands to /var/www/html
WORKDIR /var/www/html/

#Copy composer related files
COPY composer.json composer.lock ./

#Install composer dependencies in parallel fashion
RUN composer global require "hirak/prestissimo:^0.3" && \
    composer install --no-scripts --no-autoloader

# Copy source code from current host directory /var/www/html in container
COPY . /var/www/html/

#Compose Autoload
RUN composer dump-autoload --optimize

#Setting permissions
RUN chown -R nginx:nginx /var/www/html && \
    chmod -R g+rw storage && \
    rm -rf bootstrap/cache/*