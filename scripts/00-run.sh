#!/bin/bash
set -o errexit -o nounset -o pipefail

composer global require "hirak/prestissimo:^0.3"
composer install

# Running Migrations
echo "Starting migrations! ..."
php artisan migrate --force