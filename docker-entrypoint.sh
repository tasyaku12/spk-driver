#!/bin/bash
set -e

# Sesuaikan port Apache dengan PORT yang diberikan oleh Render
PORT=${PORT:-80}
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-available/000-default.conf

echo "Starting deployment on port ${PORT}..."

# Pastikan folder storage dan cache memiliki permission yang tepat
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Generate APP_KEY jika belum ada
if [ -z "$APP_KEY" ]; then
    echo "Warning: APP_KEY is not set. Generating temporary key..."
    php artisan key:generate --force
fi

# Jalankan migrasi database & seeding jika terhubung ke database
if [ -n "$DB_HOST" ]; then
    echo "Running database migrations & seeders..."
    php artisan migrate --force || echo "Migration warning: check DB credentials"
    php artisan db:seed --force || echo "Seeder warning: check DB connection"
fi

# Optimasi cache untuk production
php artisan config:clear || true
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "Application is ready! Starting Apache server..."
exec apache2-foreground
