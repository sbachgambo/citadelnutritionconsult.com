# CNC website — WordPress + WooCommerce on Railway.
# Theme and plugin code are baked into the image (redeployed on every git
# push); wp-content/uploads is expected to be a mounted Railway Volume so
# media survives redeploys. The database is Railway's managed MySQL plugin,
# not the SQLite drop-in used for local dev.

FROM wordpress:php8.3-apache

# WP-CLI, used by railway-entrypoint.sh to install WordPress, activate the
# theme/plugins, and run the one-time content seeding on first boot.
RUN curl -o /usr/local/bin/wp -sL https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
	&& chmod +x /usr/local/bin/wp

WORKDIR /var/www/html

# The base image only ships WordPress core at /usr/src/wordpress and copies
# it into /var/www/html via its own entrypoint at container *start*. We
# replace that entrypoint below, so bake core into the image ourselves here
# instead — everything under /var/www/html is then self-contained.
RUN cp -r /usr/src/wordpress/. /var/www/html/

# Bake WooCommerce into the image so it doesn't need a working database at
# build time — `wp plugin install` only downloads/extracts, it doesn't
# activate. Pin a specific version so builds are reproducible.
# 11.0.1 is the version verified working end-to-end in local testing.
RUN wp plugin install woocommerce --version=11.0.1 --allow-root

COPY wp-content/themes/cnc-theme/ wp-content/themes/cnc-theme/
COPY wp-content/plugins/cnc-core/ wp-content/plugins/cnc-core/

RUN chown -R www-data:www-data /var/www/html

COPY railway-entrypoint.sh /usr/local/bin/railway-entrypoint.sh
RUN chmod +x /usr/local/bin/railway-entrypoint.sh

ENTRYPOINT ["railway-entrypoint.sh"]
CMD ["apache2-foreground"]
