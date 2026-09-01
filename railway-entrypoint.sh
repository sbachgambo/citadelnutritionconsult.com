#!/bin/bash
# Runs once per container start. Idempotent: safe to run on every deploy —
# it only installs WordPress the first time, and cnc-core's own seeding
# functions already guard themselves against re-seeding.
set -e

: "${WORDPRESS_DB_HOST:?WORDPRESS_DB_HOST is required}"
: "${WORDPRESS_DB_USER:?WORDPRESS_DB_USER is required}"
: "${WORDPRESS_DB_PASSWORD:?WORDPRESS_DB_PASSWORD is required}"
: "${WORDPRESS_DB_NAME:?WORDPRESS_DB_NAME is required}"
: "${SITE_URL:?SITE_URL is required, e.g. https://citadelnutritionconsult.com}"

cd /var/www/html
WP="wp --path=/var/www/html --allow-root"

mkdir -p wp-content/uploads
chown -R www-data:www-data wp-content/uploads

if [ ! -f wp-config.php ]; then
	echo "Writing wp-config.php from environment..."
	$WP config create \
		--dbname="$WORDPRESS_DB_NAME" \
		--dbuser="$WORDPRESS_DB_USER" \
		--dbpass="$WORDPRESS_DB_PASSWORD" \
		--dbhost="$WORDPRESS_DB_HOST" \
		--dbprefix="${WORDPRESS_TABLE_PREFIX:-wp_}" \
		--skip-check
fi

echo "Waiting for the database..."
until $WP db check >/dev/null 2>&1; do
	sleep 2
done
echo "Database is up."

if $WP core is-installed 2>/dev/null; then
	CURRENT_URL=$($WP option get siteurl)
	if [ "$CURRENT_URL" != "$SITE_URL" ]; then
		echo "Updating site URL: $CURRENT_URL -> $SITE_URL"
		$WP option update siteurl "$SITE_URL"
		$WP option update home "$SITE_URL"
	fi
else
	echo "Installing WordPress..."
	: "${WP_ADMIN_USER:?WP_ADMIN_USER is required for first install}"
	: "${WP_ADMIN_PASSWORD:?WP_ADMIN_PASSWORD is required for first install}"
	: "${WP_ADMIN_EMAIL:?WP_ADMIN_EMAIL is required for first install}"

	$WP core install \
		--url="$SITE_URL" \
		--title="${SITE_TITLE:-Citadel Nutrition Consult}" \
		--admin_user="$WP_ADMIN_USER" \
		--admin_password="$WP_ADMIN_PASSWORD" \
		--admin_email="$WP_ADMIN_EMAIL" \
		--skip-email

	$WP rewrite structure '/%postname%/'
	$WP rewrite flush --hard

	$WP theme activate cnc-theme
	$WP plugin activate cnc-core
	$WP plugin activate woocommerce
	$WP eval 'cnc_core_maybe_seed_woocommerce();'

	$WP post delete 1 --force || true
fi

exec "$@"
