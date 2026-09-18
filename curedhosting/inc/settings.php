<?php
/**
 * CuredHosting settings page.
 *
 * One option row (`curedhosting_settings`), one Settings API page,
 * schema-driven fields. Everything the client needs to edit — plan
 * limits, promo copy and terms, FAQs, contact details — lives here.
 * No hard-coded private contact data, no secrets, ever.
 *
 * @package CuredHosting
 */

defined( 'ABSPATH' ) || exit;

/**
 * Field schema for the settings page.
 *
 * type: text | email | url | checkbox | lines | richtext | textarea
 *  - lines:    textarea, one item per line, stored as string[]
 *  - richtext: textarea, stored as sanitized HTML (limited tags)
 *  - others:   scalar strings / bool for checkbox
 *
 * @return array<int, array{id: string, title: string, callback?: string, fields: array<int, array<string, string>>}>
 */
function curedhosting_settings_schema() {
	$plan_fields = array();
	foreach ( curedhosting_defaults()['plans'] as $plan_id => $plan ) {
		$plan_fields[] = array(
			'id'       => 'plans-' . $plan_id,
			'title'    => sprintf( 'Plan: %s', $plan['name'] ),
			'callback' => 'plan',
			'plan_id'  => $plan_id,
			'fields'   => array(
				array( 'key' => 'name', 'label' => 'Plan name', 'type' => 'text' ),
				array( 'key' => 'tagline', 'label' => 'Tagline (one sentence)', 'type' => 'text' ),
				array( 'key' => 'price_note', 'label' => 'Price note (optional; leave empty to use the shared note)', 'type' => 'text' ),
				array( 'key' => 'is_wordpress', 'label' => 'This is a WordPress plan', 'type' => 'checkbox' ),
				array( 'key' => 'storage', 'label' => 'Storage (e.g. “5 GB storage”)', 'type' => 'text' ),
				array( 'key' => 'bandwidth', 'label' => 'Monthly bandwidth (e.g. “50 GB monthly bandwidth”)', 'type' => 'text' ),
				array( 'key' => 'databases', 'label' => 'MySQL databases (e.g. “3 MySQL databases”)', 'type' => 'text' ),
				array( 'key' => 'subdomains', 'label' => 'Subdomains (e.g. “10 subdomains”)', 'type' => 'text' ),
				array( 'key' => 'emails', 'label' => 'Email accounts (e.g. “10 email accounts”)', 'type' => 'text' ),
				array( 'key' => 'ftp', 'label' => 'FTP accounts (e.g. “2 FTP accounts”)', 'type' => 'text' ),
				array( 'key' => 'extras', 'label' => 'Extra features (one per line, e.g. “WordPress preinstalled”)', 'type' => 'lines' ),
				array( 'key' => 'best_for', 'label' => '“Best for” sentence', 'type' => 'text' ),
				array( 'key' => 'cta_label', 'label' => 'Card CTA label (optional; leave empty for the shared CTA)', 'type' => 'text' ),
				array( 'key' => 'cta_url', 'label' => 'Card CTA URL (optional; “/onboarding/” style or full URL)', 'type' => 'url' ),
			),
		);
	}

	$faq_fields = array();
	for ( $i = 0; $i < 8; $i++ ) {
		$faq_fields[] = array( 'key' => 'q', 'label' => sprintf( 'Question %d', $i + 1 ), 'type' => 'text' );
		$faq_fields[] = array( 'key' => 'a', 'label' => sprintf( 'Answer %d', $i + 1 ), 'type' => 'textarea' );
	}

	return array_merge(
		array(
			array(
				'id'     => 'contact',
				'title'  => 'Contact & support details',
				'fields' => array(
					array( 'key' => 'contact_email', 'label' => 'General contact email (shown on site)', 'type' => 'email' ),
					array( 'key' => 'support_email', 'label' => 'Support email (shown on site)', 'type' => 'email' ),
					array( 'key' => 'notify_email', 'label' => 'Form notification email (blank = WordPress admin email)', 'type' => 'email' ),
					array( 'key' => 'phone', 'label' => 'Phone number (optional, shown on site)', 'type' => 'text' ),
					array( 'key' => 'hours', 'label' => 'Support hours / availability line', 'type' => 'text' ),
					array( 'key' => 'location_note', 'label' => 'Short “who we are” line', 'type' => 'text' ),
				),
			),
			array(
				'id'     => 'promo',
				'title'  => 'Grand-opening promotion',
				'fields' => array(
					array( 'key' => 'promo_enabled', 'label' => 'Show the grand-opening section', 'type' => 'checkbox' ),
					array( 'key' => 'promo_kicker', 'label' => 'Kicker (small line above the heading)', 'type' => 'text' ),
					array( 'key' => 'promo_heading', 'label' => 'Heading', 'type' => 'text' ),
					array( 'key' => 'promo_intro', 'label' => 'Intro paragraph', 'type' => 'textarea' ),
					array( 'key' => 'promo_points', 'label' => 'Offer points (one per line)', 'type' => 'lines' ),
					array( 'key' => 'promo_terms', 'label' => 'Terms & disclaimers paragraph (plain text; shown in small print)', 'type' => 'textarea' ),
					array( 'key' => 'promo_link_label', 'label' => 'Terms link label', 'type' => 'text' ),
					array( 'key' => 'promo_link_url', 'label' => 'Terms link URL', 'type' => 'url' ),
				),
			),
			array(
				'id'     => 'plans',
				'title'  => 'Shared plan settings',
				'fields' => array(
					array( 'key' => 'plans_fair_use_note', 'label' => 'Fair-use note (shown under plan cards and table)', 'type' => 'textarea' ),
					array( 'key' => 'plans_price_note', 'label' => 'Shared price note (used when a plan has no price note)', 'type' => 'text' ),
					array( 'key' => 'plans_cta_label', 'label' => 'Shared card CTA label', 'type' => 'text' ),
					array( 'key' => 'plans_cta_url', 'label' => 'Shared card CTA URL', 'type' => 'url' ),
				),
			),
		),
		$plan_fields,
		array(
			array(
				'id'     => 'footer',
				'title'  => 'Footer',
				'fields' => array(
					array( 'key' => 'footer_tagline', 'label' => 'Footer tagline', 'type' => 'text' ),
					array( 'key' => 'footer_infra_note', 'label' => 'Infrastructure note (say where sites run, plainly)', 'type' => 'textarea' ),
					array( 'key' => 'footer_legal_lines', 'label' => 'Small-print lines (one per line)', 'type' => 'lines' ),
				),
			),
			array(
				'id'     => 'faq',
				'title'  => 'FAQ (leave a question empty to hide that entry)',
				'fields' => $faq_fields,
			),
		)
	);
}

/**
 * Register the settings page, sections, fields, and the single option.
 *
 * @return void
 */
function curedhosting_register_settings() {
	register_setting(
		'curedhosting_settings_group',
		'curedhosting_settings',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'curedhosting_sanitize_settings',
			'default'           => curedhosting_defaults(),
		)
	);

	foreach ( curedhosting_settings_schema() as $section ) {
		add_settings_section(
			'ch_section_' . $section['id'],
			$section['title'],
			static function () {},
			'curedhosting-settings'
		);

		foreach ( $section['fields'] as $field ) {
			add_settings_field(
				'ch_field_' . $section['id'] . '_' . $field['key'],
				$field['label'],
				'curedhosting_render_field',
				'curedhosting-settings',
				'ch_section_' . $section['id'],
				array(
					'label_for' => 'ch-input-' . $section['id'] . '-' . str_replace( array( '[', ']' ), '-', $field['key'] ),
					'section'   => $section,
					'field'     => $field,
				)
			);
		}
	}
}
add_action( 'admin_init', 'curedhosting_register_settings' );

/**
 * Admin menu entry.
 *
 * @return void
 */
function curedhosting_admin_menu() {
	add_menu_page(
		__( 'CuredHosting settings', 'curedhosting' ),
		__( 'CuredHosting', 'curedhosting' ),
		'manage_options',
		'curedhosting-settings',
		'curedhosting_render_settings_page',
		'dashicons-heart',
		59
	);
}
add_action( 'admin_menu', 'curedhosting_admin_menu' );

/**
 * Render the settings page shell.
 *
 * @return void
 */
function curedhosting_render_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'CuredHosting settings', 'curedhosting' ); ?></h1>
		<p>
			<?php esc_html_e( 'Everything here renders live through the theme’s blocks — plan cards, the grand-opening section, the FAQ, and the footer contact details. Changes save when you click “Save Changes” and apply to the whole site at once.', 'curedhosting' ); ?>
		</p>
		<form method="post" action="options.php">
			<?php
			settings_fields( 'curedhosting_settings_group' );
			do_settings_sections( 'curedhosting-settings' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}

/**
 * Generic field renderer, driven by the schema.
 *
 * @param array $args Section + field description from add_settings_field().
 * @return void
 */
function curedhosting_render_field( $args ) {
	$section = $args['section'];
	$field   = $args['field'];
	$settings = curedhosting_settings();

	if ( 'plan' === ( isset( $section['callback'] ) ? $section['callback'] : '' ) ) {
		$plan_id = $section['plan_id'];
		$value   = isset( $settings['plans'][ $plan_id ][ $field['key'] ] ) ? $settings['plans'][ $plan_id ][ $field['key'] ] : '';
		$name    = sprintf( 'curedhosting_settings[plans][%s][%s]', $plan_id, $field['key'] );
		$id      = 'ch-input-plan-' . $plan_id . '-' . $field['key'];
	} elseif ( 'faq' === $section['id'] ) {
		// FAQ fields arrive as repeating q/a pairs; flatten into indexes.
		static $faq_index = -1;
		if ( 'q' === $field['key'] ) {
			$faq_index++;
		}
		$faq   = isset( $settings['faq'] ) && is_array( $settings['faq'] ) ? $settings['faq'] : array();
		$value = isset( $faq[ $faq_index ][ $field['key'] ] ) ? $faq[ $faq_index ][ $field['key'] ] : '';
		$name  = sprintf( 'curedhosting_settings[faq][%d][%s]', $faq_index, $field['key'] );
		$id    = 'ch-input-faq-' . $faq_index . '-' . $field['key'];
	} else {
		$value = isset( $settings[ $field['key'] ] ) ? $settings[ $field['key'] ] : '';
		$name  = sprintf( 'curedhosting_settings[%s]', $field['key'] );
		$id    = $args['label_for'];
	}

	switch ( $field['type'] ) {
		case 'checkbox':
			printf(
				'<label><input type="checkbox" id="%1$s" name="%2$s" value="1"%3$s /> %4$s</label>',
				esc_attr( $id ),
				esc_attr( $name ),
				checked( ! empty( $value ), true, false ),
				esc_html__( 'Enabled', 'curedhosting' )
			);
			break;

		case 'lines':
			printf(
				'<textarea id="%1$s" name="%2$s" rows="5" class="large-text code">%3$s</textarea><p class="description">%4$s</p>',
				esc_attr( $id ),
				esc_attr( $name ),
				esc_textarea( is_array( $value ) ? implode( "\n", $value ) : (string) $value ),
				esc_html__( 'One item per line. Empty lines are ignored.', 'curedhosting' )
			);
			break;

		case 'textarea':
		case 'richtext':
			printf(
				'<textarea id="%1$s" name="%2$s" rows="4" class="large-text">%3$s</textarea>',
				esc_attr( $id ),
				esc_attr( $name ),
				esc_textarea( (string) $value )
			);
			break;

		case 'email':
			printf(
				'<input type="email" id="%1$s" name="%2$s" value="%3$s" class="regular-text" />',
				esc_attr( $id ),
				esc_attr( $name ),
				esc_attr( (string) $value )
			);
			break;

		case 'url':
			printf(
				'<input type="url" id="%1$s" name="%2$s" value="%3$s" class="regular-text" placeholder="/onboarding/ or https://…" />',
				esc_attr( $id ),
				esc_attr( $name ),
				esc_attr( (string) $value )
			);
			break;

		default: // text
			printf(
				'<input type="text" id="%1$s" name="%2$s" value="%3$s" class="regular-text" />',
				esc_attr( $id ),
				esc_attr( $name ),
				esc_attr( (string) $value )
			);
	}
}

/**
 * Sanitize the whole settings array on save.
 *
 * Unknown keys are dropped; known keys are sanitized by type. This is the
 * only place raw input from the settings form is accepted.
 *
 * @param mixed $input Raw submitted value.
 * @return array<string, mixed>
 */
function curedhosting_sanitize_settings( $input ) {
	if ( ! is_array( $input ) ) {
		$input = array();
	}

	$out      = array();
	$defaults = curedhosting_defaults();

	/* Scalar top-level fields. */
	$scalar_rules = array(
		'contact_email'      => 'email',
		'support_email'      => 'email',
		'notify_email'       => 'email',
		'phone'              => 'text',
		'hours'              => 'text',
		'location_note'      => 'text',
		'footer_tagline'     => 'text',
		'footer_infra_note'  => 'textarea',
		'promo_kicker'       => 'text',
		'promo_heading'      => 'text',
		'promo_intro'        => 'textarea',
		'promo_terms'        => 'textarea',
		'promo_link_label'   => 'text',
		'promo_link_url'     => 'url',
		'plans_fair_use_note' => 'textarea',
		'plans_price_note'   => 'text',
		'plans_cta_label'    => 'text',
		'plans_cta_url'      => 'url',
	);

	foreach ( $scalar_rules as $key => $rule ) {
		$raw = isset( $input[ $key ] ) ? $input[ $key ] : '';
		switch ( $rule ) {
			case 'email':
				$out[ $key ] = sanitize_email( (string) $raw );
				break;
			case 'url':
				$out[ $key ] = esc_url_raw( (string) $raw );
				break;
			case 'textarea':
				$out[ $key ] = sanitize_textarea_field( (string) $raw );
				break;
			default:
				$out[ $key ] = sanitize_text_field( (string) $raw );
		}
	}

	/* Checkboxes: presence = true. */
	$out['promo_enabled'] = ! empty( $input['promo_enabled'] );

	/* Line lists. */
	foreach ( array( 'promo_points', 'footer_legal_lines' ) as $key ) {
		$out[ $key ] = curedhosting_sanitize_lines( isset( $input[ $key ] ) ? $input[ $key ] : array() );
	}

	/* Plans. */
	$out['plans'] = array();
	foreach ( $defaults['plans'] as $plan_id => $plan_defaults ) {
		$raw_plan   = isset( $input['plans'][ $plan_id ] ) && is_array( $input['plans'][ $plan_id ] ) ? $input['plans'][ $plan_id ] : array();
		$clean_plan = array();
		foreach ( $plan_defaults as $plan_key => $plan_default ) {
			$raw_value = isset( $raw_plan[ $plan_key ] ) ? $raw_plan[ $plan_key ] : null;
			if ( 'is_wordpress' === $plan_key ) {
				$clean_plan[ $plan_key ] = ! empty( $raw_value );
			} elseif ( 'extras' === $plan_key ) {
				$clean_plan[ $plan_key ] = curedhosting_sanitize_lines( $raw_value );
			} elseif ( 'cta_url' === $plan_key ) {
				$clean_plan[ $plan_key ] = esc_url_raw( (string) $raw_value );
			} else {
				$clean_plan[ $plan_key ] = sanitize_text_field( (string) $raw_value );
			}
		}
		$out['plans'][ $plan_id ] = $clean_plan;
	}

	/* FAQ entries. */
	$out['faq'] = array();
	$raw_faq    = isset( $input['faq'] ) && is_array( $input['faq'] ) ? $input['faq'] : array();
	$max        = count( $defaults['faq'] );
	for ( $i = 0; $i < $max; $i++ ) {
		$item = isset( $raw_faq[ $i ] ) && is_array( $raw_faq[ $i ] ) ? $raw_faq[ $i ] : array();
		$out['faq'][] = array(
			'q' => isset( $item['q'] ) ? sanitize_text_field( (string) $item['q'] ) : '',
			'a' => isset( $item['a'] ) ? sanitize_textarea_field( (string) $item['a'] ) : '',
		);
	}

	return $out;
}

/**
 * Sanitize a "one item per line" value (textarea string or array).
 *
 * @param mixed $raw Raw input.
 * @return string[] Clean lines, empties removed, capped at 30.
 */
function curedhosting_sanitize_lines( $raw ) {
	if ( is_array( $raw ) ) {
		$raw = implode( "\n", array_map( 'strval', $raw ) );
	}
	$raw   = (string) $raw;
	$lines = preg_split( '/\r\n|\r|\n/', $raw );
	$out   = array();
	foreach ( (array) $lines as $line ) {
		$line = sanitize_text_field( trim( (string) $line ) );
		if ( '' !== $line ) {
			$out[] = $line;
		}
		if ( count( $out ) >= 30 ) {
			break;
		}
	}
	return $out;
}
