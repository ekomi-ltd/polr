#!/bin/bash
set -o errexit -o nounset -o pipefail

initialize_system() {
  echo "Initializing Polr container ..."

  export APP_KEY=${APP_KEY:-}
  export APP_ENV=${APP_ENV:-development}
  export APP_DEBUG=${APP_DEBUG:-true}
  export APP_URL=${APP_URL:-http://localhost}
  export APP_LOG=${APP_LOG:-errorlog}
  export APP_DOMAIN=${APP_DOMAIN:-localhost}

  export DB_HOST=${DB_HOST:-mysql}
  export DB_DATABASE=${DB_DATABASE:-polr}
  export DB_PREFIX=${DB_PREFIX:-}
  export DB_USERNAME=${DB_USERNAME:-root}
  export DB_PASSWORD=${DB_PASSWORD:-root}
  export DB_PORT=${DB_PORT:-3306}

  export CACHE_DRIVER=${CACHE_DRIVER:-apc}

  export SESSION_DRIVER=${SESSION_DRIVER:-}
  export SESSION_DOMAIN=${SESSION_DOMAIN:-$APP_URL}
  export SESSION_SECURE_COOKIE=${SESSION_SECURE_COOKIE:-}

  export QUEUE_DRIVER=${QUEUE_DRIVER:-database}

  export PHP_MAX_CHILDREN=${PHP_MAX_CHILDREN:-5}

  # AWS Flag
  export ENV_FROM_AWS=${ENV_FROM_AWS:-true}
  export FORCE_HTTPS=${FORCE_HTTPS:-true}
  echo "key-${APP_KEY}-key"
  if [[ "${APP_KEY}" == "" ]]; then
    keygen="$(php artisan key:generate)"
    appkey=$(echo "${keygen}" | grep -oP '(?<=\[).*(?=\])')
    echo "ERROR: Please set the 'APP_KEY=${appkey}' environment variable at runtime or in docker-compose.yml and re-launch"
    exit 0
  fi

  /usr/local/bin/confd -onetime -backend env

  # remove empty lines
  sed '/^.*=""$/d'  -i /var/www/html/.env
  sed 's/""/"/g'  -i /var/www/html/.env

  # Running Migrations
  php artisan migrate

  rm -rf bootstrap/cache/*
}

start_system() {
  initialize_system
  echo "Starting Polr! ..."
  # php artisan config:cache
}

start_system && exec "$@"

