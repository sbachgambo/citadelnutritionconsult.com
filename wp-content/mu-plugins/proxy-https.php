<?php
/**
 * Railway (and most PaaS hosts) terminate TLS at their edge and forward
 * plain HTTP to the container, setting X-Forwarded-Proto so the app knows
 * the original request was HTTPS. WordPress's is_ssl() only checks
 * $_SERVER['HTTPS'] directly, so without this, WP thinks every request is
 * insecure — forcing all URLs to http:// and breaking WooCommerce/login
 * cookies. Must-use so it loads before anything else, no activation needed.
 */

defined( 'ABSPATH' ) || exit;

if (
	isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] )
	&& 'https' === strtolower( $_SERVER['HTTP_X_FORWARDED_PROTO'] )
) {
	$_SERVER['HTTPS'] = 'on';
}
