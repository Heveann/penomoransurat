#!/bin/bash

echo "==> Starting application setup..."

# Generate application key if not set
if [ -z "$APP_KEY" ]; then
    echo "==> Generating APP_KEY..."
    php artisan key:generate --force
fi

# Cache configuration for performance
echo "==> Caching config..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Run database migrations (with retry for DB startup delay)
echo "==> Running migrations..."
MAX_RETRIES=5
RETRY_COUNT=0
until php artisan migrate --force 2>/dev/null; do
    RETRY_COUNT=$((RETRY_COUNT + 1))
    if [ $RETRY_COUNT -ge $MAX_RETRIES ]; then
        echo "==> WARNING: Migration failed after $MAX_RETRIES attempts, starting anyway..."
        break
    fi
    echo "==> Database not ready, retrying in 5s... ($RETRY_COUNT/$MAX_RETRIES)"
    sleep 5
done

# Create storage link
php artisan storage:link --force 2>/dev/null || true

# Fix permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

echo "==> Starting Laravel server on port ${PORT:-8080}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
