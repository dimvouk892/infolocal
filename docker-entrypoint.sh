#!/bin/bash
# Fix storage permissions so www-data can write (upload/delete)
if [ -d /var/www/storage ]; then
    chown -R www-data:www-data /var/www/storage 2>/dev/null || true
    chmod -R 775 /var/www/storage 2>/dev/null || true
fi
if [ -d /var/www/bootstrap/cache ]; then
    chown -R www-data:www-data /var/www/bootstrap/cache 2>/dev/null || true
    chmod -R 775 /var/www/bootstrap/cache 2>/dev/null || true
fi
exec "$@"
