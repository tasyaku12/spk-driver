#!/bin/bash
set -e

# Sesuaikan port Apache dengan PORT yang diberikan oleh Render
PORT=${PORT:-80}
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-available/000-default.conf

echo "🚀 Starting deployment on port ${PORT}..."

# Pastikan folder storage dan cache memiliki permission yang tepat
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Buat file .env dari environment variables jika belum ada
if [ ! -f /var/www/html/.env ]; then
    echo "📝 Creating .env file from environment variables..."
    cat > /var/www/html/.env << ENVFILE
APP_NAME="${APP_NAME:-SPK Driver}"
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY:-}
APP_DEBUG=${APP_DEBUG:-false}
APP_URL=${APP_URL:-http://localhost}

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/database/database.sqlite

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=${MAIL_MAILER:-smtp}
MAIL_HOST=${MAIL_HOST:-smtp.gmail.com}
MAIL_PORT=${MAIL_PORT:-465}
MAIL_USERNAME=${MAIL_USERNAME:-}
MAIL_PASSWORD=${MAIL_PASSWORD:-}
MAIL_ENCRYPTION=${MAIL_ENCRYPTION:-ssl}
MAIL_FROM_ADDRESS="${MAIL_FROM_ADDRESS:-noreply@example.com}"
MAIL_FROM_NAME="${MAIL_FROM_NAME:-SPK Driver}"
ENVFILE
fi

# Generate APP_KEY jika belum ada
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating APP_KEY..."
    php artisan key:generate --force
fi

# Setup SQLite database
SQLITE_DB="/var/www/html/database/database.sqlite"
if [ ! -f "$SQLITE_DB" ]; then
    echo "📦 Creating SQLite database..."
    touch "$SQLITE_DB"
    chown www-data:www-data "$SQLITE_DB"
    chmod 664 "$SQLITE_DB"

    echo "🗃️ Running database migrations..."
    php artisan migrate --force

    echo "🌱 Running database seeders..."
    php artisan db:seed --force

    echo "✅ Database initialized successfully!"
else
    echo "📂 SQLite database already exists, running pending migrations..."
    php artisan migrate --force || echo "⚠️ Migration warning"
fi

# Optimasi cache untuk production
php artisan config:clear || true
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Buat symlink storage jika belum ada
php artisan storage:link || true

echo "✅ Application is ready! Starting Apache server..."
exec apache2-foreground
