<?php
/**
 * CNC Theme functions.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Theme setup: FSE support, thumbnails, editor styles.
 * Patterns in /patterns are auto-registered by core because their header
 * comments declare a Theme URI matching this theme (no manual registration needed).
 */
function cnc_theme_setup() {
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style' ) );
	add_theme_support( 'custom-logo', array(
		'height'      => 140,
		'width'       => 400,
		'flex-height' => true,
		'flex-width'  => true,
	) );

	add_editor_style( 'assets/css/editor.css' );
}
add_action( 'after_setup_theme', 'cnc_theme_setup' );

/**
 * Register custom block pattern category so CNC section patterns
 * group together in the inserter instead of mixing with core patterns.
 */
function cnc_register_pattern_categories() {
	register_block_pattern_category(
		'cnc-sections',
		array( 'label' => __( 'CNC Sections', 'cnc-theme' ) )
	);
}
add_action( 'init', 'cnc_register_pattern_categories' );

/**
 * Front-end stylesheet (style.css theme header + any extra rules) plus the
 * hover-lift / scroll-reveal / photo-tilt motion layer.
 */
function cnc_enqueue_assets() {
	$version = wp_get_theme()->get( 'Version' );
	wp_enqueue_style( 'cnc-theme-style', get_stylesheet_uri(), array(), $version );
	wp_enqueue_style( 'cnc-theme-interactions', get_theme_file_uri( 'assets/css/interactions.css' ), array( 'cnc-theme-style' ), $version );
	wp_enqueue_script( 'cnc-theme-interactions', get_theme_file_uri( 'assets/js/interactions.js' ), array(), $version, true );
}
add_action( 'wp_enqueue_scripts', 'cnc_enqueue_assets' );

/**
 * Sets the theme's own logo/favicon as defaults so the site isn't blank
 * before staff visit Appearance → Site Identity — WP prefers the site's own
 * Custom Logo / Site Icon the moment either is set there, so this only
 * fills the gap for a fresh install.
 */
function cnc_theme_default_logo( $html ) {
	if ( ! empty( $html ) || is_customize_preview() ) {
		return $html;
	}
	$src = get_theme_file_uri( 'assets/images/brand/cnc-logo.png' );
	return '<a href="' . esc_url( home_url( '/' ) ) . '" class="custom-logo-link" rel="home"><img src="' . esc_url( $src ) . '" class="custom-logo" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" /></a>';
}
add_filter( 'get_custom_logo', 'cnc_theme_default_logo' );

function cnc_theme_default_favicon() {
	if ( has_site_icon() ) {
		return;
	}
	echo '<link rel="icon" href="' . esc_url( get_theme_file_uri( 'assets/images/brand/favlogo.png' ) ) . '">' . "\n";
}
add_action( 'wp_head', 'cnc_theme_default_favicon', 1 );
