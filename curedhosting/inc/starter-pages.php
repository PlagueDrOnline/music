<?php
/**
 * CuredHosting starter pages.
 *
 * After activation, admins see a one-time notice offering to create the
 * standard page set (with pattern references) and set the front page.
 * Nothing is created silently: it requires a click, a nonce, and
 * manage_options capability. Existing pages with the same slug are
 * never overwritten.
 *
 * @package CuredHosting
 */

defined( 'ABSPATH' ) || exit;

/**
 * The standard page set: slug => { title, pattern }.
 * Pages reference their pattern by slug, so they render live and can be
 * overridden in the editor without breaking the source pattern.
 *
 * @return array<string, array{title: string, pattern: string}>
 */
function curedhosting_starter_pages() {
	return array(
		'home'               => array(
			'title'   => __( 'Home', 'curedhosting' ),
			'pattern' => '', // front-page.html template provides the content.
		),
		'wordpress-hosting'  => array(
			'title'   => __( 'WordPress Hosting', 'curedhosting' ),
			'pattern' => 'curedhosting/plans-wordpress',
		),
		'linux-hosting'      => array(
			'title'   => __( 'Linux Hosting', 'curedhosting' ),
			'pattern' => 'curedhosting/plans-linux',
		),
		'hosting-comparison' => array(
			'title'   => __( 'Hosting Comparison', 'curedhosting' ),
			'pattern' => 'curedhosting/comparison',
		),
		'free-migration'     => array(
			'title'   => __( 'Free Migration', 'curedhosting' ),
			'pattern' => 'curedhosting/migration',
		),
		'grand-opening'      => array(
			'title'   => __( 'Grand Opening', 'curedhosting' ),
			'pattern' => 'curedhosting/grand-opening-landing',
		),
		'about'              => array(
			'title'   => __( 'About CuredHosting', 'curedhosting' ),
			'pattern' => 'curedhosting/about-content',
		),
		'contact'            => array(
			'title'   => __( 'Contact & Support', 'curedhosting' ),
			'pattern' => 'curedhosting/contact-form',
		),
		'onboarding'         => array(
			'title'   => __( 'Onboarding Questionnaire', 'curedhosting' ),
			'pattern' => 'curedhosting/onboarding-form',
		),
		'fair-use'           => array(
			'title'   => __( 'Fair-Use Policy', 'curedhosting' ),
			'pattern' => 'curedhosting/policy-fair-use',
		),
		'acceptable-use'     => array(
			'title'   => __( 'Acceptable-Use Policy', 'curedhosting' ),
			'pattern' => 'curedhosting/policy-acceptable-use',
		),
		'restricted-plugins' => array(
			'title'   => __( 'Restricted WordPress Plugins', 'curedhosting' ),
			'pattern' => 'curedhosting/policy-banned-plugins',
		),
		'privacy-policy'     => array(
			'title'   => __( 'Privacy Policy', 'curedhosting' ),
			'pattern' => 'curedhosting/policy-privacy-placeholder',
		),
		'terms-of-service'   => array(
			'title'   => __( 'Terms of Service', 'curedhosting' ),
			'pattern' => 'curedhosting/policy-terms-placeholder',
		),
	);
}

/**
 * Flag that pages still need creating (set on activation).
 *
 * @return void
 */
function curedhosting_flag_pending_pages() {
	if ( false === get_option( 'curedhosting_pages_created' ) ) {
		update_option( 'curedhosting_pages_created', false );
	}
}
add_action( 'after_switch_theme', 'curedhosting_flag_pending_pages' );

/**
 * One-time admin notice with the create-pages button.
 *
 * @return void
 */
function curedhosting_pages_notice() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( get_option( 'curedhosting_pages_created' ) ) {
		return;
	}
	?>
	<div class="notice notice-info">
		<p>
			<?php esc_html_e( 'CuredHosting theme: create the standard page set (plans, migration, about, contact, onboarding, policies) and set the front page?', 'curedhosting' ); ?>
		</p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="ch_setup_pages" />
			<?php wp_nonce_field( 'ch_setup_pages' ); ?>
			<?php submit_button( __( 'Create standard pages', 'curedhosting' ), 'secondary', 'ch-create-pages', false ); ?>
		</form>
	</div>
	<?php
}
add_action( 'admin_notices', 'curedhosting_pages_notice' );

/**
 * Create the pages. Hooked to admin-post.
 *
 * @return void
 */
function curedhosting_handle_setup_pages() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to do this.', 'curedhosting' ) );
	}
	check_admin_referer( 'ch_setup_pages' );

	foreach ( curedhosting_starter_pages() as $slug => $page ) {
		$existing = get_page_by_path( $slug );
		if ( $existing instanceof WP_Post ) {
			continue; // Never overwrite.
		}

		$content = '';
		if ( '' !== $page['pattern'] ) {
			$content = sprintf(
				'<!-- wp:pattern {"slug":"%s"} /-->',
				esc_js( $page['pattern'] )
			);
		}

		wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => $page['title'],
				'post_name'    => $slug,
				'post_content' => $content,
			)
		);
	}

	/* Set the static front page. */
	$home = get_page_by_path( 'home' );
	if ( $home instanceof WP_Post ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home->ID );
	}

	update_option( 'curedhosting_pages_created', true );

	wp_safe_redirect( admin_url( 'index.php?ch_pages=created' ) );
	exit;
}
add_action( 'admin_post_ch_setup_pages', 'curedhosting_handle_setup_pages' );

/**
 * Confirmation notice after pages are created.
 *
 * @return void
 */
function curedhosting_pages_created_notice() {
	if ( ! current_user_can( 'manage_options' ) || empty( $_GET['ch_pages'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only notice flag.
		return;
	}
	echo '<div class="notice notice-success is-dismissible"><p>';
	esc_html_e( 'CuredHosting: standard pages created and the front page set. Review them under Pages, and adjust the navigation in Appearance → Editor.', 'curedhosting' );
	echo '</p></div>';
}
add_action( 'admin_notices', 'curedhosting_pages_created_notice' );
