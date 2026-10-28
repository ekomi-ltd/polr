# The base linux container
FROM richarvey/nginx-php-fpm:1.9.1

LABEL maintainer="Coeus Team <aman.mukhtar@coeus-solutions.de>"

# The port this docker will listen to
EXPOSE 8000

#Environment variables
ENV RUN_SCRIPTS=1
ENV SKIP_CHOWN=1
ENV SKIP_COMPOSER=1

# Set current working directory for host commands to /var/www/html
WORKDIR /var/www/html/

# NOTE: Composer is intentionally not upgraded from v1.10.5 to v2.x at this time.
# Since the current build works fine with Composer 1.x, upgrading it now would
# increase the testing scope beyond what this change intends to address.
# The build continues to work because composer install reads directly from
# composer.lock and downloads packages via dist URLs, bypassing the Packagist
# v1 API (shut down Sep 2025). Note that composer update/require commands will
# fail until the upgrade is carried out. This will be addressed as part of the
# planned framework and PHP version upgrade.

#Copy composer related files
COPY composer.json composer.lock ./

#Install composer dependencies
RUN composer install --no-autoloader --no-dev -o

# Copy source code from current host directory /var/www/html in container
COPY . /var/www/html/

#Compose Autoload
RUN composer dump-autoload --optimize

# CVE-2026-109618 / CVE-2026-109619 mitigations — consolidated security.ini
COPY deploy/files/security.ini /tmp/zz-security.ini
RUN PHP_CONF_DIR=$(php --ini | grep "Scan for additional .ini files" | awk -F': ' '{print $2}' | xargs) && \
    cp /tmp/zz-security.ini "${PHP_CONF_DIR}/zz-security.ini" && \
    chmod 644 "${PHP_CONF_DIR}/zz-security.ini" && \
    rm /tmp/zz-security.ini

#Setting permissions
RUN chown -R nginx:nginx /var/www/html && \
    chmod -R g+rw storage && \
    rm -rf bootstrap/cache/*