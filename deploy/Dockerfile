# The base linux container
FROM richarvey/nginx-php-fpm:1.9.1

# Person / organization responsible for maintenance
MAINTAINER Coeus Team <aman.mukhtar@coeus-solutions.de>

# The port this docker will listen to
EXPOSE 8000

#Environment variables
ENV RUN_SCRIPTS=1
ENV SKIP_CHOWN=1
ENV SKIP_COMPOSER=1

RUN whoami

# Set current working directory for host commands to /var/www/html
WORKDIR /var/www/html/

#Copy composer related files
COPY composer.json composer.lock ./

RUN chown -R nginx:nginx /var/www/html
USER nginx:nginx
RUN whoami

#Install composer dependencies in parallel fashion
RUN composer global require "hirak/prestissimo:^0.3" && \
    composer install --no-autoloader --no-dev -o

# Copy source code from current host directory /var/www/html in container
COPY . /var/www/html/

#Compose Autoload
RUN composer dump-autoload --optimize

RUN whoami

USER root

RUN whoami

#Setting permissions
RUN chmod -R g+rw storage && \
    rm -rf bootstrap/cache/*