#!/bin/sh
set -e

# Render injects $PORT (not always 80) and expects the container to listen on it.
PORT="${PORT:-80}"
sed -ri "s/Listen [0-9]+/Listen ${PORT}/" /etc/apache2/ports.conf
sed -ri "s/:80>/:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# Values come from Render's env vars at container start, so caching happens
# here (not at build time, when they aren't set yet).
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec "$@"
