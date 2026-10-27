#!/bin/sh
set -e

composer update -n 

php artisan key:generate

apache2-foreground
