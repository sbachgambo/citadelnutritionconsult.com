<?php
/**
 * Plugin Name: CNC Core
 * Description: Custom post types and fields for Citadel Nutrition Consult (Services, Testimonials, FAQ, Digital Products). Companion plugin for the CNC Theme — keeps content structures editable from WP admin without touching theme code.
 * Version: 0.1.0
 * Author: B Koncept
 * Text Domain: cnc-core
 * Requires PHP: 7.4
 */

defined( 'ABSPATH' ) || exit;

define( 'CNC_CORE_VERSION', '0.1.0' );
define( 'CNC_CORE_PATH', plugin_dir_path( __FILE__ ) );

require_once CNC_CORE_PATH . 'includes/post-types.php';
require_once CNC_CORE_PATH . 'includes/meta.php';
require_once CNC_CORE_PATH . 'includes/seed-content.php';
require_once CNC_CORE_PATH . 'includes/seed-woocommerce.php';

function cnc_core_activate() {
	cnc_core_register_post_types();
	cnc_core_seed_content();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'cnc_core_activate' );

function cnc_core_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'cnc_core_deactivate' );
