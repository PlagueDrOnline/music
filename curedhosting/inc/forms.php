<?php
/**
 * CuredHosting form engine.
 *
 * Two native forms — contact/support and the onboarding questionnaire —
 * handled through admin-post.php with:
 *
 *   - nonce verification per form,
 *   - an invisible honeypot field,
 *   - per-IP rate limiting via short transients,
 *   - strict per-field sanitization and whitelisting,
 *   - storage as PRIVATE posts (post type `ch_submission`), so data stays
 *     local to this WordPress installation and visible only to admins,
 *   - optional email notification to the configured address.
 *
 * No third-party services are contacted. Nothing phones home. If a site
 * owner later wires in a form/CRM plugin, that is an explicit choice
 * documented in docs/content-guide.md — not a default.
 *
 * @package CuredHosting
 */

defined( 'ABSPATH' ) || exit;

/**
 * Private post type that stores form submissions locally.
 *
 * @return void
 */
function curedhosting_register_submission_cpt() {
	register_post_type(
		'ch_submission',
		array(
			'labels'          => array(
				'name'          => __( 'Form submissions', 'curedhosting' ),
				'singular_name' => __( 'Form submission', 'curedhosting' ),
				'menu_name'     => __( 'Submissions', 'curedhosting' ),
			),
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => 'curedhosting-settings',
			'show_in_rest'    => false,
			'capability_type' => 'post',
			'capabilities'    => array(
				'create_posts' => 'do_not_allow', // View-only archive; no manual creation.
			),
			'map_meta_cap'    => true,
			'supports'        => array( 'title', 'editor' ),
			'rewrite'         => false,
		)
	);
}
add_action( 'init', 'curedhosting_register_submission_cpt' );

/**
 * Field schemas for both forms.
 *
 * type: text | email | textarea | select | radio | checkbox
 * 'options' whitelists allowed values for select/radio.
 *
 * @param string $type Form type: contact | onboarding.
 * @return array<string, array<string, mixed>>
 */
function curedhosting_form_schema( $type ) {
	if ( 'contact' === $type ) {
		return array(
			'contact_name'  => array( 'label' => __( 'Name', 'curedhosting' ), 'type' => 'text', 'required' => true, 'max' => 200 ),
			'contact_email' => array( 'label' => __( 'Email', 'curedhosting' ), 'type' => 'email', 'required' => true, 'max' => 200 ),
			'topic'         => array(
				'label'   => __( 'Topic', 'curedhosting' ),
				'type'    => 'select',
				'options' => array( 'general', 'support', 'migration', 'grand-opening', 'other' ),
				'default' => 'general',
			),
			'message'       => array( 'label' => __( 'Message', 'curedhosting' ), 'type' => 'textarea', 'required' => true, 'max' => 5000 ),
			'consent'       => array( 'label' => __( 'Permission to reply', 'curedhosting' ), 'type' => 'checkbox', 'required' => true ),
		);
	}

	return array(
		'contact_name'      => array( 'label' => __( 'Name', 'curedhosting' ), 'type' => 'text', 'required' => true, 'max' => 200 ),
		'contact_email'     => array( 'label' => __( 'Email', 'curedhosting' ), 'type' => 'email', 'required' => true, 'max' => 200 ),
		'domain'            => array( 'label' => __( 'Domain (current or planned)', 'curedhosting' ), 'type' => 'text', 'required' => true, 'max' => 200 ),
		'current_host'      => array( 'label' => __( 'Current host', 'curedhosting' ), 'type' => 'text', 'max' => 200 ),
		'registrar'         => array( 'label' => __( 'Domain registrar', 'curedhosting' ), 'type' => 'text', 'max' => 200 ),
		'dns_provider'      => array( 'label' => __( 'DNS provider', 'curedhosting' ), 'type' => 'text', 'max' => 200 ),
		'hosting_preference' => array(
			'label'   => __( 'Hosting preference', 'curedhosting' ),
			'type'    => 'radio',
			'options' => array( 'wordpress', 'linux', 'unsure' ),
			'default' => 'unsure',
		),
		'sites_count'       => array( 'label' => __( 'Number of sites', 'curedhosting' ), 'type' => 'text', 'max' => 20 ),
		'databases_count'   => array( 'label' => __( 'Number of MySQL databases', 'curedhosting' ), 'type' => 'text', 'max' => 20 ),
		'subdomains_count'  => array( 'label' => __( 'Number of subdomains', 'curedhosting' ), 'type' => 'text', 'max' => 20 ),
		'staging'           => array(
			'label'   => __( 'Staging requirement', 'curedhosting' ),
			'type'    => 'radio',
			'options' => array( 'yes', 'no', 'unsure' ),
			'default' => 'unsure',
		),
		'storage_estimate'  => array(
			'label'   => __( 'Approximate storage', 'curedhosting' ),
			'type'    => 'select',
			'options' => array( 'under-5gb', '5-10gb', '10-20gb', 'over-20gb', 'unsure' ),
			'default' => 'unsure',
		),
		'traffic_estimate'  => array(
			'label'   => __( 'Approximate monthly traffic', 'curedhosting' ),
			'type'    => 'select',
			'options' => array( 'under-1k', '1k-10k', '10k-50k', 'over-50k', 'unsure' ),
			'default' => 'unsure',
		),
		'email_needs'       => array( 'label' => __( 'Email requirements', 'curedhosting' ), 'type' => 'textarea', 'max' => 2000 ),
		'migration'         => array(
			'label'   => __( 'Migration needs', 'curedhosting' ),
			'type'    => 'radio',
			'options' => array( 'yes', 'new-site', 'unsure' ),
			'default' => 'unsure',
		),
		'migration_details' => array( 'label' => __( 'Migration details', 'curedhosting' ), 'type' => 'textarea', 'max' => 2000 ),
		'current_problems'  => array( 'label' => __( 'Current problems with hosting', 'curedhosting' ), 'type' => 'textarea', 'max' => 2000 ),
		'launch_timing'     => array(
			'label'   => __( 'Preferred launch timing', 'curedhosting' ),
			'type'    => 'radio',
			'options' => array( 'asap', '2-4-weeks', '1-3-months', 'flexible' ),
			'default' => 'flexible',
		),
		'consent'           => array( 'label' => __( 'Permission to contact', 'curedhosting' ), 'type' => 'checkbox', 'required' => true ),
	);
}

/**
 * Wire the four admin-post hooks (logged-in + logged-out × 2 forms).
 *
 * @return void
 */
function curedhosting_register_form_handlers() {
	add_action( 'admin_post_ch_contact', 'curedhosting_handle_contact_form' );
	add_action( 'admin_post_nopriv_ch_contact', 'curedhosting_handle_contact_form' );
	add_action( 'admin_post_ch_onboarding', 'curedhosting_handle_onboarding_form' );
	add_action( 'admin_post_nopriv_ch_onboarding', 'curedhosting_handle_onboarding_form' );
}
add_action( 'init', 'curedhosting_register_form_handlers' );

/**
 * Contact form entry point.
 *
 * @return void
 */
function curedhosting_handle_contact_form() {
	curedhosting_process_form( 'contact' );
}

/**
 * Onboarding form entry point.
 *
 * @return void
 */
function curedhosting_handle_onboarding_form() {
	curedhosting_process_form( 'onboarding' );
}

/**
 * Shared form pipeline: verify → sanitize → validate → store → notify.
 * Always redirects (PRG) — never renders output on POST.
 *
 * @param string $type Form type: contact | onboarding.
 * @return void
 */
function curedhosting_process_form( $type ) {
	$redirect = wp_get_referer();
	if ( ! $redirect || false !== strpos( $redirect, 'admin-post.php' ) ) {
		$redirect = home_url( '/' );
	}
	$redirect = remove_query_arg( array( 'ch_status', 'ch_form', 'ch_token' ), $redirect );

	/* 1) Nonce. */
	if ( ! isset( $_POST['ch_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['ch_nonce'] ) ), 'ch_' . $type . '_form' ) ) {
		curedhosting_fail_form( $type, $redirect, array( 'form' => __( 'Your session expired. Please refresh the page and try again.', 'curedhosting' ) ), array() );
	}

	/* 2) Rate limit: max 4 submissions per IP per 30 minutes. */
	$ip_key = 'ch_rl_' . sha256_of_ip();
	$count  = (int) get_transient( $ip_key );
	if ( $count >= 4 ) {
		curedhosting_fail_form( $type, $redirect, array( 'form' => __( 'Too many submissions from this connection. Please try again in a little while.', 'curedhosting' ) ), array() );
	}
	set_transient( $ip_key, $count + 1, 30 * MINUTE_IN_SECONDS );

	/* 3) Honeypot: bots fill the hidden field. Fail silently. */
	if ( isset( $_POST['ch_hp'] ) && '' !== trim( (string) wp_unslash( $_POST['ch_hp'] ) ) ) {
		wp_safe_redirect( add_query_arg( array( 'ch_form' => $type, 'ch_status' => 'success' ), $redirect ) . '#ch-form-' . $type );
		exit;
	}

	/* 4) Sanitize + validate each field. */
	$schema = curedhosting_form_schema( $type );
	$values = array();
	$errors = array();

	foreach ( $schema as $key => $field ) {
		$raw = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- sanitized below per type.

		switch ( $field['type'] ) {
			case 'email':
				$value = sanitize_email( (string) $raw );
				if ( ! empty( $field['required'] ) && ! is_email( $value ) ) {
					$errors[ $key ] = __( 'Please enter a valid email address.', 'curedhosting' );
				}
				break;

			case 'textarea':
				$value = sanitize_textarea_field( (string) $raw );
				$value = mb_substr( $value, 0, isset( $field['max'] ) ? (int) $field['max'] : 5000 );
				if ( ! empty( $field['required'] ) && '' === trim( $value ) ) {
					$errors[ $key ] = __( 'This field is required.', 'curedhosting' );
				}
				break;

			case 'select':
			case 'radio':
				$value = sanitize_key( (string) $raw );
				if ( ! in_array( $value, $field['options'], true ) ) {
					$value = isset( $field['default'] ) ? $field['default'] : '';
				}
				break;

			case 'checkbox':
				$value = empty( $raw ) ? '' : 'yes';
				if ( ! empty( $field['required'] ) && '' === $value ) {
					$errors[ $key ] = __( 'Please tick this box so we have your permission to reply.', 'curedhosting' );
				}
				break;

			default: // text
				$value = sanitize_text_field( (string) $raw );
				$value = mb_substr( $value, 0, isset( $field['max'] ) ? (int) $field['max'] : 200 );
				if ( ! empty( $field['required'] ) && '' === trim( $value ) ) {
					$errors[ $key ] = __( 'This field is required.', 'curedhosting' );
				}
		}

		$values[ $key ] = $value;
	}

	if ( ! empty( $errors ) ) {
		curedhosting_fail_form( $type, $redirect, $errors, $values );
	}

	/* 5) Store locally as a private post. */
	curedhosting_store_submission( $type, $schema, $values );

	/* 6) Notify the configured address. */
	curedhosting_notify_submission( $type, $schema, $values );

	wp_safe_redirect( add_query_arg( array( 'ch_form' => $type, 'ch_status' => 'success' ), $redirect ) . '#ch-form-' . $type );
	exit;
}

/**
 * Redirect back with errors + values stored in a short-lived transient.
 *
 * @param string $type     Form type.
 * @param string $redirect Base redirect URL.
 * @param array  $errors   Field errors.
 * @param array  $values   Sanitized values for repopulation.
 * @return void
 */
function curedhosting_fail_form( $type, $redirect, $errors, $values ) {
	$token = wp_generate_password( 24, false, false );
	set_transient(
		'ch_fs_' . $token,
		array(
			'errors' => $errors,
			'values' => $values,
		),
		HOUR_IN_SECONDS
	);

	wp_safe_redirect(
		add_query_arg(
			array(
				'ch_form'   => $type,
				'ch_status' => 'error',
				'ch_token'  => rawurlencode( $token ),
			),
			$redirect
		) . '#ch-form-' . $type
	);
	exit;
}

/**
 * Store a submission as a private post. Admins-only, local, deletable.
 *
 * @param string $type   Form type.
 * @param array  $schema Field schema.
 * @param array  $values Sanitized values.
 * @return void
 */
function curedhosting_store_submission( $type, $schema, $values ) {
	/**
	 * Allow disabling local storage of submissions.
	 *
	 * @param bool $store Whether to store submissions as private posts.
	 */
	if ( ! apply_filters( 'curedhosting_store_submissions', true ) ) {
		return;
	}

	$lines = array();
	foreach ( $schema as $key => $field ) {
		$value = isset( $values[ $key ] ) ? $values[ $key ] : '';
		if ( '' === $value ) {
			$value = '—';
		}
		$lines[] = $field['label'] . ': ' . $value;
	}

	$title = sprintf(
		/* translators: 1: form type, 2: submitter name, 3: date. */
		__( '[%1$s] %2$s — %3$s', 'curedhosting' ),
		'contact' === $type ? __( 'Contact', 'curedhosting' ) : __( 'Onboarding', 'curedhosting' ),
		isset( $values['contact_name'] ) && '' !== $values['contact_name'] ? $values['contact_name'] : __( '(no name given)', 'curedhosting' ),
		wp_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) )
	);

	wp_insert_post(
		array(
			'post_type'    => 'ch_submission',
			'post_status'  => 'private',
			'post_title'   => sanitize_text_field( $title ),
			'post_content' => implode( "\n", $lines ),
		)
	);
}

/**
 * Email a plain-text copy of the submission to the configured address.
 *
 * @param string $type   Form type.
 * @param array  $schema Field schema.
 * @param array  $values Sanitized values.
 * @return void
 */
function curedhosting_notify_submission( $type, $schema, $values ) {
	$to = curedhosting_notify_email();
	if ( ! is_email( $to ) ) {
		return;
	}

	$subject = sprintf(
		/* translators: 1: form type, 2: site name. */
		__( '[%1$s] New %2$s form submission', 'curedhosting' ),
		get_bloginfo( 'name' ),
		'contact' === $type ? __( 'contact', 'curedhosting' ) : __( 'onboarding', 'curedhosting' )
	);

	$lines   = array( __( 'New submission received:', 'curedhosting' ), '' );
	foreach ( $schema as $key => $field ) {
		$value     = isset( $values[ $key ] ) ? $values[ $key ] : '';
		$lines[]   = $field['label'] . ': ' . ( '' !== $value ? $value : '—' );
	}
	$lines[] = '';
	$lines[] = __( 'Stored locally as a private post under “Submissions” in wp-admin.', 'curedhosting' );

	wp_mail( $to, $subject, implode( "\n", $lines ) );
}

/**
 * Privacy-respecting rate-limit key: salted hash of the remote IP.
 * The raw IP is never stored.
 *
 * @return string
 */
function sha256_of_ip() {
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'unknown';
	return hash_hmac( 'sha256', $ip, wp_salt( 'auth' ) );
}
