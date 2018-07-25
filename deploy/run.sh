#!/bin/bash
set -o errexit -o nounset -o pipefail

# Running Migrations
php artisan migrate

/usr/bin/supervisord -n -c /etc/supervisor/supervisord.conf
