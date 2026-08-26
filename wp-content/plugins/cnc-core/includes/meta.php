<?php
/**
 * Post meta for Digital Products, exposed to the block editor so the
 * price and Selar checkout link can be bound into patterns via core
 * block bindings (no code changes needed per product).
 */

defined( 'ABSPATH' ) || exit;

function cnc_core_register_meta() {

	register_post_meta( 'digital_product', 'cnc_price', array(
		'show_in_rest'      => true,
		'single'            => true,
		'type'              => 'string',
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
		'auth_callback'     => function() {
			return current_user_can( 'edit_posts' );
		},
	) );

	register_post_meta( 'digital_product', 'cnc_selar_url', array(
		'show_in_rest'      => true,
		'single'            => true,
		'type'              => 'string',
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
		'auth_callback'     => function() {
			return current_user_can( 'edit_posts' );
		},
	) );
}
add_action( 'init', 'cnc_core_register_meta' );
