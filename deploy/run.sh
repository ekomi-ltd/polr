#!/bin/bash
set -o errexit -o nounset -o pipefail

/usr/bin/supervisord -n -c /etc/supervisor/supervisord.conf
