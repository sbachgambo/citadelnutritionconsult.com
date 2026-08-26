<?php
/**
 * Custom post type registrations.
 */

defined( 'ABSPATH' ) || exit;

function cnc_core_register_post_types() {

	register_post_type( 'service', array(
		'labels' => array(
			'name'          => __( 'Services', 'cnc-core' ),
			'singular_name' => __( 'Service', 'cnc-core' ),
			'add_new_item'  => __( 'Add New Service', 'cnc-core' ),
			'edit_item'     => __( 'Edit Service', 'cnc-core' ),
		),
		'public'       => true,
		'show_in_rest' => true,
		'menu_icon'    => 'dashicons-clipboard',
		'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
		'has_archive'  => false,
		'rewrite'      => array( 'slug' => 'services' ),
	) );

	register_post_type( 'testimonial', array(
		'labels' => array(
			'name'          => __( 'Testimonials', 'cnc-core' ),
			'singular_name' => __( 'Testimonial', 'cnc-core' ),
			'add_new_item'  => __( 'Add New Testimonial', 'cnc-core' ),
			'edit_item'     => __( 'Edit Testimonial', 'cnc-core' ),
		),
		'public'       => true,
		'show_in_rest' => true,
		'menu_icon'    => 'dashicons-format-quote',
		'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
		'has_archive'  => false,
		'rewrite'      => array( 'slug' => 'testimonials' ),
	) );
	// Title = client name, editor/excerpt = quote.

	register_post_type( 'faq_item', array(
		'labels' => array(
			'name'          => __( 'FAQ', 'cnc-core' ),
			'singular_name' => __( 'FAQ Item', 'cnc-core' ),
			'add_new_item'  => __( 'Add New FAQ Item', 'cnc-core' ),
			'edit_item'     => __( 'Edit FAQ Item', 'cnc-core' ),
		),
		'public'       => true,
		'show_in_rest' => true,
		'menu_icon'    => 'dashicons-editor-help',
		'supports'     => array( 'title', 'editor', 'page-attributes' ),
		'has_archive'  => false,
		'rewrite'      => array( 'slug' => 'faq' ),
	) );
	// Title = question, editor = answer.

	register_post_type( 'digital_product', array(
		'labels' => array(
			'name'          => __( 'Digital Products', 'cnc-core' ),
			'singular_name' => __( 'Digital Product', 'cnc-core' ),
			'add_new_item'  => __( 'Add New Digital Product', 'cnc-core' ),
			'edit_item'     => __( 'Edit Digital Product', 'cnc-core' ),
		),
		'public'       => true,
		'show_in_rest' => true,
		'menu_icon'    => 'dashicons-welcome-learn-more',
		'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
		'has_archive'  => false,
		'rewrite'      => array( 'slug' => 'learn' ),
	) );
	// Title = course/e-book name, excerpt = short description, meta = price + Selar checkout URL.
}
add_action( 'init', 'cnc_core_register_post_types' );
