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

/**
 * Floating WhatsApp chat button, shown site-wide. Filter cnc_whatsapp_number
 * to override the number without editing this file.
 */
function cnc_theme_whatsapp_button() {
	$number = apply_filters( 'cnc_whatsapp_number', '2349046758079' );
	$text   = rawurlencode( "Hi, I'd like some help" );
	$url    = esc_url( 'https://wa.me/' . $number . '?text=' . $text );
	?>
	<a href="<?php echo $url; ?>" class="whatsapp-fab" target="_blank" rel="noopener" aria-label="Chat with us on WhatsApp">
		<svg viewBox="0 0 32 32" fill="white" xmlns="http://www.w3.org/2000/svg"><path d="M16.004 2.667c-7.363 0-13.333 5.97-13.333 13.333 0 2.353.615 4.66 1.784 6.687L2.667 29.333l6.815-1.787a13.27 13.27 0 0 0 6.522 1.72h.006c7.362 0 13.333-5.97 13.333-13.333 0-3.563-1.388-6.913-3.907-9.432a13.24 13.24 0 0 0-9.432-3.834zm0 24.4h-.005a11.06 11.06 0 0 1-5.638-1.543l-.404-.24-4.045 1.061 1.08-3.944-.264-.406a11.05 11.05 0 0 1-1.693-5.895c0-6.106 4.968-11.073 11.075-11.073 2.958 0 5.738 1.153 7.83 3.246a10.996 10.996 0 0 1 3.24 7.832c-.001 6.106-4.969 11.073-11.076 11.073zm6.073-8.29c-.333-.167-1.966-.97-2.27-1.08-.305-.11-.527-.167-.75.167-.222.334-.86 1.08-1.055 1.303-.194.222-.388.25-.72.083-.334-.167-1.41-.52-2.685-1.657-.993-.886-1.663-1.98-1.858-2.314-.194-.333-.02-.514.146-.68.15-.15.334-.389.5-.583.167-.194.222-.333.334-.556.11-.222.055-.417-.028-.583-.083-.167-.75-1.807-1.028-2.474-.27-.65-.545-.56-.75-.57-.194-.01-.417-.012-.64-.012-.222 0-.583.083-.888.417-.305.333-1.164 1.138-1.164 2.775 0 1.638 1.192 3.22 1.358 3.443.167.222 2.346 3.583 5.685 5.023.795.343 1.415.549 1.898.703.797.253 1.523.217 2.097.132.64-.095 1.966-.803 2.243-1.58.278-.775.278-1.44.194-1.58-.083-.14-.305-.222-.639-.389z"/></svg>
	</a>
	<?php
}
add_action( 'wp_footer', 'cnc_theme_whatsapp_button' );
