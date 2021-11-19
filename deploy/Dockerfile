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

# Set current working directory for host commands to /var/www/html
WORKDIR /var/www/html/

# Copy source code from current host directory /var/www/html in container
COPY . /var/www/html/

RUN composer global require "hirak/prestissimo:^0.3" && \
    composer install --no-dev -o

#Setting permissions
RUN chown -R nginx:nginx /var/www/html && \
    chmod -R g+rw storage && \
    rm -rf bootstrap/cache/*