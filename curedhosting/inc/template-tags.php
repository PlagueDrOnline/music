<?php
/**
 * CuredHosting template helpers.
 *
 * Small read-only helpers used by templates, patterns, and block render
 * callbacks. All output produced with these helpers is already safe to
 * echo where documented; render callbacks still escape explicitly.
 *
 * @package CuredHosting
 */

defined( 'ABSPATH' ) || exit;

/**
 * Return the full settings array: saved values merged over defaults.
 *
 * Nested structures (plans) are merged per-plan so a partial save can
 * never wipe unedited plans.
 *
 * @return array<string, mixed>
 */
function curedhosting_settings() {
	$defaults = curedhosting_defaults();
	$saved    = get_option( 'curedhosting_settings', array() );

	if ( ! is_array( $saved ) ) {
		$saved = array();
	}

	$merged = wp_parse_args( $saved, $defaults );

	// Merge each plan individually.
	$merged['plans'] = array();
	foreach ( $defaults['plans'] as $plan_id => $plan_defaults ) {
		$saved_plan    = isset( $saved['plans'][ $plan_id ] ) && is_array( $saved['plans'][ $plan_id ] ) ? $saved['plans'][ $plan_id ] : array();
		$merged['plans'][ $plan_id ] = wp_parse_args( $saved_plan, $plan_defaults );
	}

	// FAQ: prefer saved list when present; skip empty questions on render.
	if ( isset( $saved['faq'] ) && is_array( $saved['faq'] ) ) {
		$merged['faq'] = $saved['faq'];
	}

	// Promo bullet list: prefer saved when present; skip empties on render.
	if ( isset( $saved['promo_points'] ) && is_array( $saved['promo_points'] ) ) {
		$merged['promo_points'] = $saved['promo_points'];
	}

	if ( isset( $saved['footer_legal_lines'] ) && is_array( $saved['footer_legal_lines'] ) ) {
		$merged['footer_legal_lines'] = $saved['footer_legal_lines'];
	}

	/**
	 * Filter the resolved settings.
	 *
	 * @param array $merged Merged settings.
	 */
	return apply_filters( 'curedhosting_settings', $merged );
}

/**
 * Get one top-level setting.
 *
 * @param string $key     Setting key.
 * @param mixed  $default Fallback if missing.
 * @return mixed
 */
function curedhosting_setting( $key, $default = '' ) {
	$settings = curedhosting_settings();
	return isset( $settings[ $key ] ) ? $settings[ $key ] : $default;
}

/**
 * Where should form notifications go? Defaults to the WordPress admin
 * email — never a hardcoded address.
 *
 * @return string
 */
function curedhosting_notify_email() {
	$email = (string) curedhosting_setting( 'notify_email' );
	if ( '' === $email || ! is_email( $email ) ) {
		$email = (string) get_option( 'admin_email' );
	}
	return $email;
}

/**
 * Get plans, optionally filtered by group.
 *
 * @param string $group all|wordpress|linux.
 * @return array<string, array> Plan id => merged plan.
 */
function curedhosting_plans( $group = 'all' ) {
	$settings = curedhosting_settings();
	$plans    = isset( $settings['plans'] ) && is_array( $settings['plans'] ) ? $settings['plans'] : array();

	if ( 'all' === $group ) {
		return $plans;
	}

	$want_wp = ( 'wordpress' === $group );
	$out     = array();
	foreach ( $plans as $id => $plan ) {
		if ( ! empty( $plan['is_wordpress'] ) === $want_wp ) {
			$out[ $id ] = $plan;
		}
	}
	return $out;
}

/**
 * Build the ordered limit list for one plan card: the six structured
 * limits plus any extra lines (e.g. "WordPress preinstalled").
 *
 * @param array $plan Merged plan array.
 * @return string[]
 */
function curedhosting_plan_limits( $plan ) {
	$items = array();
	foreach ( curedhosting_plan_limit_fields() as $field ) {
		if ( ! empty( $plan[ $field['key'] ] ) ) {
			$items[] = (string) $plan[ $field['key'] ];
		}
	}
	if ( ! empty( $plan['extras'] ) && is_array( $plan['extras'] ) ) {
		foreach ( $plan['extras'] as $extra ) {
			$extra = trim( (string) $extra );
			if ( '' !== $extra ) {
				$items[] = $extra;
			}
		}
	}
	return $items;
}

/**
 * FAQ items with empty questions removed.
 *
 * @return array<int, array{q: string, a: string}>
 */
function curedhosting_faq_items() {
	$settings = curedhosting_settings();
	$faq      = isset( $settings['faq'] ) && is_array( $settings['faq'] ) ? $settings['faq'] : array();
	$out      = array();
	foreach ( $faq as $item ) {
		if ( ! is_array( $item ) ) {
			continue;
		}
		$q = isset( $item['q'] ) ? trim( (string) $item['q'] ) : '';
		$a = isset( $item['a'] ) ? trim( (string) $item['a'] ) : '';
		if ( '' !== $q && '' !== $a ) {
			$out[] = array(
				'q' => $q,
				'a' => $a,
			);
		}
	}
	return $out;
}

/**
 * Resolve a URL setting that may be site-relative ("/onboarding/").
 *
 * @param string $url Raw URL from settings.
 * @return string Escaped-safe absolute URL ('' if empty/invalid).
 */
function curedhosting_resolve_url( $url ) {
	$url = trim( (string) $url );
	if ( '' === $url ) {
		return '';
	}
	if ( 0 === strpos( $url, '/' ) && 0 !== strpos( $url, '//' ) ) {
		$url = home_url( $url );
	}
	return esc_url( $url );
}

/* -------------------------------------------------------------------------
 * Form state helpers (see inc/forms.php). Forms re-render previous values
 * and errors after a failed submission using a short-lived transient.
 * ---------------------------------------------------------------------- */

/**
 * Read (and consume) the stored form state for this request, if any.
 *
 * @param string $type Form type: 'contact' or 'onboarding'.
 * @return array{errors: array<string,string>, values: array<string,string>}
 */
function curedhosting_form_state( $type ) {
	$state = array(
		'errors' => array(),
		'values' => array(),
	);

	if ( empty( $_GET['ch_token'] ) || empty( $_GET['ch_form'] ) || sanitize_key( wp_unslash( $_GET['ch_form'] ) ) !== $type ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only display state.
		return $state;
	}

	$token   = preg_replace( '/[^a-zA-Z0-9]/', '', sanitize_text_field( wp_unslash( $_GET['ch_token'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$stored  = get_transient( 'ch_fs_' . $token );

	if ( is_array( $stored ) ) {
		delete_transient( 'ch_fs_' . $token ); // Consume once: never replayed.
		$state['errors'] = isset( $stored['errors'] ) && is_array( $stored['errors'] ) ? $stored['errors'] : array();
		$state['values'] = isset( $stored['values'] ) && is_array( $stored['values'] ) ? $stored['values'] : array();
	}

	return $state;
}

/**
 * Was the given form just submitted successfully?
 *
 * @param string $type Form type.
 * @return bool
 */
function curedhosting_form_success( $type ) {
	if ( empty( $_GET['ch_status'] ) || empty( $_GET['ch_form'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only display state.
		return false;
	}
	return 'success' === sanitize_key( wp_unslash( $_GET['ch_status'] ) ) // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		&& sanitize_key( wp_unslash( $_GET['ch_form'] ) ) === $type; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
}

/**
 * Escaped value attribute for re-populating a field.
 *
 * @param array  $state Form state.
 * @param string $key   Field key.
 * @return string
 */
function curedhosting_field_value( $state, $key ) {
	return isset( $state['values'][ $key ] ) ? esc_attr( $state['values'][ $key ] ) : '';
}

/**
 * ' selected' when a select/radio option matches the stored value.
 *
 * @param array  $state Form state.
 * @param string $key   Field key.
 * @param string $value Option value.
 * @return string
 */
function curedhosting_field_selected( $state, $key, $value ) {
	if ( isset( $state['values'][ $key ] ) && (string) $state['values'][ $key ] === (string) $value ) {
		return ' selected';
	}
	return '';
}

/**
 * ' checked' when a radio/checkbox option matches the stored value.
 *
 * @param array  $state Form state.
 * @param string $key   Field key.
 * @param string $value Option value.
 * @return string
 */
function curedhosting_field_checked( $state, $key, $value ) {
	if ( isset( $state['values'][ $key ] ) && (string) $state['values'][ $key ] === (string) $value ) {
		return ' checked';
	}
	return '';
}

/**
 * One error message for a field, or empty string.
 *
 * @param array  $state Form state.
 * @param string $key   Field key.
 * @return string
 */
function curedhosting_field_error( $state, $key ) {
	return isset( $state['errors'][ $key ] ) ? $state['errors'][ $key ] : '';
}
