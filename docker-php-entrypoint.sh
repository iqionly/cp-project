#!/bin/sh
set -e

cp .env.example .env

composer install -n --no-cache

if [ ! -f storage/.initialized ]; then
    php artisan key:generate
    php artisan storage:link
    php artisan migrate --seed --force

    touch storage/.initialized
fi

exec apache2-foreground
