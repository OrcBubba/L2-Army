#!/bin/sh
set -e

PORT="${PORT:-80}"

# Render assigns PORT; Apache must listen on it.
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/\${PORT}/${PORT}/g" /etc/apache2/sites-available/000-default.conf

DOMAIN="${APP_DOMAIN:-${RENDER_EXTERNAL_HOSTNAME:-localhost}}"
CDN="${APP_CDN:-${DOMAIN}/assets}"

cat > /var/www/html/.env <<EOF
APP_NAME="${APP_NAME:-L2 Army}"
APP_SCHEME="${APP_SCHEME:-https}"
APP_DOMAIN="${DOMAIN}"
APP_CDN="${CDN}"
DEMO_MODE="${DEMO_MODE:-false}"
LOGIN_HOST="${LOGIN_HOST:-127.0.0.1}"
LOGIN_PORT="${LOGIN_PORT:-2106}"
GAME_HOST="${GAME_HOST:-127.0.0.1}"
GAME_PORT="${GAME_PORT:-7777}"
DB_HOST="${DB_HOST:-127.0.0.1}"
DB_USER="${DB_USER:-root}"
DB_PASS="${DB_PASS:-}"
DB_NAME="${DB_NAME:-lineage2army}"
DB_GAME_HOST="${DB_GAME_HOST:-${DB_HOST:-127.0.0.1}}"
DB_GAME_USER="${DB_GAME_USER:-${DB_USER:-root}}"
DB_GAME_PASS="${DB_GAME_PASS:-${DB_PASS:-}}"
DB_GAME_NAME="${DB_GAME_NAME:-${DB_NAME:-lineage2army}}"
STRIPE_SECRET_KEY="${STRIPE_SECRET_KEY:-}"
EOF

# Render serves ACM at the service root, not /acm
cat > /var/www/html/public/.htaccess <<'EOF'
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . index.php [L]
</IfModule>
EOF

touch /var/www/html/.version
chown www-data:www-data /var/www/html/.version /var/www/html/.env
chmod 666 /var/www/html/.version

exec apache2-foreground
