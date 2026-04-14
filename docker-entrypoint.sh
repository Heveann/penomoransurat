#!/bin/bash
set -e

# Generate application key if not set
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Cache configuration for performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
php artisan migrate --force

# Create storage link
php artisan storage:link --force 2>/dev/null || true

# Fix permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Update Apache port to match Railway's PORT env var
if [ ! -z "$PORT" ]; then
    sed -i "s/80/$PORT/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf
fi

# Start Apache
exec apache2-foreground
