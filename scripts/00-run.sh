#!/bin/bash
set -o errexit -o nounset -o pipefail

# Running Migrations
echo "Starting migrations! ..."
php artisan migrate --force