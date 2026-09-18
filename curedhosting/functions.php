<?php
/**
 * CuredHosting theme bootstrap.
 *
 * A calm, trust-first block theme for CuredHosting.
 * "The cure for the common hosting headache."
 *
 * Architecture (see docs/architecture.md):
 * - Block theme: theme.json + HTML templates + PHP block patterns.
 * - Structured content (plans, promo, FAQ, contact) lives in one option
 *   managed by a native Settings API page (inc/settings.php) and is
 *   rendered by server-side dynamic blocks (inc/blocks.php).
 * - Forms (contact, onboarding) are native admin-post handlers with
 *   nonces, honeypot, rate limiting, private-post storage (inc/forms.php).
 * - No required plugins, no remote fonts, no third-party calls.
 *
 * @package CuredHosting
 */

defined( 'ABSPATH' ) || exit;

/**
 * Theme version, used for asset cache busting.
 */
define( 'CUREDHOSTING_VERSION', '1.0.0' );

/**
 * Text domain (must match the Text Domain header in style.css).
 */
define( 'CUREDHOSTING_TEXT_DOMAIN', 'curedhosting' );

require_once get_template_directory() . '/inc/defaults.php';
require_once get_template_directory() . '/inc/template-tags.php';
require_once get_template_directory() . '/inc/settings.php';
require_once get_template_directory() . '/inc/blocks.php';
require_once get_template_directory() . '/inc/forms.php';
require_once get_template_directory() . '/inc/starter-pages.php';

/**
 * Theme setup.
 *
 * @return void
 */
function curedhosting_setup() {
	load_theme_textdomain( CUREDHOSTING_TEXT_DOMAIN, get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'align-wide' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 320,
			'width'       => 320,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	// Editor picks up form/card/focus styles so content previews honestly.
	add_editor_style( 'assets/css/theme.css' );
}
add_action( 'after_setup_theme', 'curedhosting_setup' );

/**
 * Register the theme's block pattern category.
 *
 * @return void
 */
function curedhosting_register_pattern_category() {
	register_block_pattern_category(
		'curedhosting',
		array(
			'label' => __( 'CuredHosting', 'curedhosting' ),
		)
	);
}
add_action( 'init', 'curedhosting_register_pattern_category' );

/**
 * Front-end assets. Two files total, both small, both local.
 *
 * @return void
 */
function curedhosting_enqueue_assets() {
	wp_enqueue_style(
		'curedhosting-theme',
		get_template_uri() . '/assets/css/theme.css',
		array(),
		CUREDHOSTING_VERSION
	);

	wp_enqueue_script(
		'curedhosting-theme',
		get_template_uri() . '/assets/js/theme.js',
		array(),
		CUREDHOSTING_VERSION,
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'curedhosting_enqueue_assets' );

/**
 * Skip link for keyboard users. Templates put htmlAnchor "ch-main"
 * on their main content group so this has a real target.
 *
 * @return void
 */
function curedhosting_skip_link() {
	printf(
		'<a class="ch-skip-link" href="#ch-main">%s</a>',
		esc_html__( 'Skip to main content', 'curedhosting' )
	);
}
add_action( 'wp_body_open', 'curedhosting_skip_link' );

/**
 * Install the supplied CuredHosting logo as the site's custom logo on
 * theme activation (only if no custom logo is already set).
 *
 * The canonical logo assets live in assets/images/:
 *   - perfectlogo-256.png  (THE supplied brand raster — preferred)
 *   - curedhosting-logo.svg (faithful vector stand-in, used if the raster
 *     is not present; e.g. before the real asset is dropped in)
 *   - curedhosting-logo.png (rasterized stand-in fallback for WP media)
 *
 * The supplied PNG was delivered from
 * https://curedhosting.com/wp-content/themes/cured-hosting/assets/perfectlogo-256.png
 * — drop that exact file into assets/images/ and it takes precedence
 * automatically on the next theme activation (or set it manually under
 * Appearance → Customize → Site Identity).
 *
 * @return void
 */
function curedhosting_install_default_logo() {
	if ( get_theme_mod( 'custom_logo' ) ) {
		return; // Respect an existing choice.
	}

	$candidates = array(
		'perfectlogo-256.png',
		'curedhosting-logo.png',
	);

	$source = '';
	foreach ( $candidates as $candidate ) {
		$path = get_template_directory() . '/assets/images/' . $candidate;
		if ( file_exists( $path ) ) {
			$source = $path;
			break;
		}
	}

	if ( '' === $source ) {
		return;
	}

	if ( ! function_exists( 'media_handle_sideload' ) ) {
		require_once ABSPATH . 'wp-admin/includes/image.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
	}

	// Copy to a temp file with a proper extension for the sideload API.
	$file_name = basename( $source );
	$tmp       = wp_tempnam( $file_name );
	if ( ! $tmp ) {
		return;
	}

	if ( ! copy( $source, $tmp ) ) {
		unlink( $tmp );
		return;
	}

	$sideload = array(
		'tmp_name' => $tmp,
		'name'     => $file_name,
	);

	$attachment_id = media_handle_sideload( $sideload, 0, __( 'CuredHosting logo', 'curedhosting' ) );

	if ( file_exists( $tmp ) ) {
		unlink( $tmp );
	}

	if ( is_wp_error( $attachment_id ) ) {
		return;
	}

	set_theme_mod( 'custom_logo', $attachment_id );
}
add_action( 'after_switch_theme', 'curedhosting_install_default_logo' );
