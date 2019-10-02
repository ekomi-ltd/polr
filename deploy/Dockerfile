FROM openresty/openresty:alpine

MAINTAINER eKomi Team <anawaz@ekomi-services.com>

EXPOSE 8000

ARG APP_ENV=production

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

# forward request and error logs to docker log collector
RUN ln -sf /dev/stdout /usr/local/openresty/nginx/logs/access.log && \
    ln -sf /dev/stderr /usr/local/openresty/nginx/logs/error.log && \
    ln -sf /dev/stdout /var/log/php7/error.log && \
    ln -sf /dev/stderr /var/log/php7/error.log

# create certificate for dev APP_DOMAIN
RUN if [ "$APP_ENV" = "local" ] ; then \
    mkdir /etc/ssl/localcerts && cd /etc/ssl/localcerts && \
    openssl genpkey -algorithm RSA -pkeyopt rsa_keygen_bits:2048 -out server.pass.key && \
    openssl rsa -passin pass:x -in server.pass.key -out server.key && \
    rm server.pass.key && \
    openssl req -new -key server.key -out server.csr -subj "/C=UK/ST=Warwickshire/L=Leamington/O=OrgName/OU=IT Department/CN=$APP_DOMAIN" && \
    openssl x509 -req -days 365 -in server.csr -signkey server.key -out server.crt ;fi

RUN addgroup -S www-data && \
    adduser -S -s /bin/bash -u 1001 -G www-data www-data

RUN touch /var/run/nginx.pid && \
    chown -R www-data:www-data /var/run/nginx.pid /etc/php7/php-fpm.d

RUN mkdir -p /var/www/html && \
    mkdir -p /usr/share/nginx/cache && \
    mkdir -p /var/cache/nginx && \
    mkdir -p /var/lib/nginx && \
    wget https://github.com/kelseyhightower/confd/releases/download/v0.16.0/confd-0.16.0-linux-amd64 -O /usr/local/bin/confd && \
    chmod u+x /usr/local/bin/confd && \
    chown -R www-data:www-data /var/www /usr/share/nginx/cache /var/cache/nginx /var/lib/nginx/ /usr/local/bin/confd /etc/nginx

RUN if [ "$APP_ENV" = "local" ] ; then \
    chown -R www-data:www-data /etc/ssl/localcerts ;fi

# Install composer
RUN wget https://getcomposer.org/installer -O /tmp/composer-setup.php && \
    wget https://composer.github.io/installer.sig -O /tmp/composer-setup.sig && \
    php -r "if (hash('SHA384', file_get_contents('/tmp/composer-setup.php')) !== trim(file_get_contents('/tmp/composer-setup.sig'))) { unlink('/tmp/composer-setup.php'); echo 'Invalid installer' . PHP_EOL; exit(1); }" && \
    php /tmp/composer-setup.php --version=$COMPOSER_VERSION --install-dir=bin && \
    php -r "unlink('/tmp/composer-setup.php');"

WORKDIR /var/www/html/

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html && chown -R www-data:www-data /home/www-data

USER 1001
RUN php /bin/composer.phar global require "hirak/prestissimo:^0.3" && \
    php /bin/composer.phar install --no-dev -o && \
    rm -rf bootstrap/cache/*

COPY deploy/files/supervisord.conf /etc/supervisor/supervisord.conf
COPY deploy/files/nginx.conf /usr/local/openresty/nginx/conf/nginx.conf
COPY deploy/files/confd /etc/confd
COPY deploy/entrypoint.sh /sbin/entrypoint.sh
COPY deploy/run.sh /sbin/run.sh

USER root
RUN chmod g+rwx /var/run/nginx.pid && \
    chmod -R g+rw /var/www /usr/share/nginx/cache /var/cache/nginx /var/lib/nginx/ /etc/php7/php-fpm.d storage && \
    chown www-data:www-data /sbin/run.sh && chmod u+x /sbin/run.sh

USER 1001

ENTRYPOINT ["/sbin/entrypoint.sh"]

CMD ["/sbin/run.sh"]
