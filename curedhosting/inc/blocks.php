<?php
/**
 * CuredHosting dynamic blocks.
 *
 * Five server-rendered blocks, no JavaScript, no build step:
 *
 *   curedhosting/plans             Plan cards from settings (all|wordpress|linux)
 *   curedhosting/comparison-table  One accessible table, same data as the cards
 *   curedhosting/faq               details/summary accordion from settings
 *   curedhosting/promo             Grand-opening section from settings
 *   curedhosting/support-summary   Contact email / phone / hours from settings
 *
 * Single source of truth: everything renders from the settings option,
 * so cards, table, promo, and footer can never drift apart.
 *
 * @package CuredHosting
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register all dynamic blocks.
 *
 * @return void
 */
function curedhosting_register_blocks() {
	register_block_type(
		'curedhosting/plans',
		array(
			'api_version'   => 2,
			'title'         => __( 'CuredHosting Plans', 'curedhosting' ),
			'description'   => __( 'Plan cards with exact limits, rendered from CuredHosting settings.', 'curedhosting' ),
			'category'      => 'curedhosting',
			'attributes'    => array(
				'group' => array(
					'type'    => 'string',
					'default' => 'all',
					'enum'    => array( 'all', 'wordpress', 'linux' ),
				),
			),
			'supports'      => array(
				'html' => false,
			),
			'render_callback' => 'curedhosting_render_plans_block',
		)
	);

	register_block_type(
		'curedhosting/comparison-table',
		array(
			'api_version'   => 2,
			'title'         => __( 'CuredHosting Comparison Table', 'curedhosting' ),
			'description'   => __( 'Side-by-side plan comparison rendered from CuredHosting settings.', 'curedhosting' ),
			'category'      => 'curedhosting',
			'supports'      => array(
				'html' => false,
			),
			'render_callback' => 'curedhosting_render_comparison_block',
		)
	);

	register_block_type(
		'curedhosting/faq',
		array(
			'api_version'   => 2,
			'title'         => __( 'CuredHosting FAQ', 'curedhosting' ),
			'description'   => __( 'Accessible FAQ accordion rendered from CuredHosting settings.', 'curedhosting' ),
			'category'      => 'curedhosting',
			'supports'      => array(
				'html' => false,
			),
			'render_callback' => 'curedhosting_render_faq_block',
		)
	);

	register_block_type(
		'curedhosting/promo',
		array(
			'api_version'   => 2,
			'title'         => __( 'CuredHosting Grand Opening', 'curedhosting' ),
			'description'   => __( 'Grand-opening offer section rendered from CuredHosting settings.', 'curedhosting' ),
			'category'      => 'curedhosting',
			'supports'      => array(
				'html' => false,
			),
			'render_callback' => 'curedhosting_render_promo_block',
		)
	);

	register_block_type(
		'curedhosting/support-summary',
		array(
			'api_version'   => 2,
			'title'         => __( 'CuredHosting Support Summary', 'curedhosting' ),
			'description'   => __( 'Support email, phone, and hours from CuredHosting settings.', 'curedhosting' ),
			'category'      => 'curedhosting',
			'attributes'    => array(
				'variant' => array(
					'type'    => 'string',
					'default' => 'list',
					'enum'    => array( 'list', 'footer' ),
				),
			),
			'supports'      => array(
				'html' => false,
			),
			'render_callback' => 'curedhosting_render_support_block',
		)
	);
}
add_action( 'init', 'curedhosting_register_blocks' );

/* -------------------------------------------------------------------------
 * curedhosting/plans
 * ---------------------------------------------------------------------- */

/**
 * Render plan cards.
 *
 * @param array $attributes Block attributes.
 * @return string
 */
function curedhosting_render_plans_block( $attributes ) {
	$group  = isset( $attributes['group'] ) ? $attributes['group'] : 'all';
	$plans  = curedhosting_plans( $group );
	$notice = (string) curedhosting_setting( 'plans_fair_use_note' );

	if ( empty( $plans ) ) {
		return '';
	}

	$html = '<div class="ch-plans ch-plans--' . esc_attr( $group ) . '">';

	foreach ( $plans as $plan ) {
		$name       = (string) ( isset( $plan['name'] ) ? $plan['name'] : '' );
		$tagline    = (string) ( isset( $plan['tagline'] ) ? $plan['tagline'] : '' );
		$price_note = (string) ( isset( $plan['price_note'] ) ? $plan['price_note'] : '' );
		if ( '' === $price_note ) {
			$price_note = (string) curedhosting_setting( 'plans_price_note' );
		}
		$best_for  = (string) ( isset( $plan['best_for'] ) ? $plan['best_for'] : '' );
		$cta_label = (string) ( isset( $plan['cta_label'] ) ? $plan['cta_label'] : '' );
		$cta_url   = (string) ( isset( $plan['cta_url'] ) ? $plan['cta_url'] : '' );
		if ( '' === $cta_label ) {
			$cta_label = (string) curedhosting_setting( 'plans_cta_label' );
		}
		if ( '' === $cta_url ) {
			$cta_url = (string) curedhosting_setting( 'plans_cta_url' );
		}
		$cta_url = curedhosting_resolve_url( $cta_url );

		$html .= '<article class="ch-plan-card">';
		$html .= '<h3 class="ch-plan-card__name">' . esc_html( $name ) . '</h3>';

		if ( '' !== $tagline ) {
			$html .= '<p class="ch-plan-card__tagline">' . esc_html( $tagline ) . '</p>';
		}

		$limits = curedhosting_plan_limits( $plan );
		if ( ! empty( $limits ) ) {
			$html .= '<ul class="ch-plan-card__limits">';
			foreach ( $limits as $limit ) {
				$html .= '<li>' . esc_html( $limit ) . '</li>';
			}
			$html .= '</ul>';
		}

		if ( '' !== $best_for ) {
			$html .= '<p class="ch-plan-card__bestfor"><strong>' . esc_html__( 'Best for:', 'curedhosting' ) . '</strong> ' . esc_html( $best_for ) . '</p>';
		}

		if ( '' !== $price_note ) {
			$html .= '<p class="ch-plan-card__price">' . esc_html( $price_note ) . '</p>';
		}

		if ( '' !== $cta_url && '' !== $cta_label ) {
			$html .= '<a class="ch-btn ch-btn--primary ch-plan-card__cta" href="' . esc_url( $cta_url ) . '">' . esc_html( $cta_label ) . '</a>';
		}

		if ( '' !== $notice ) {
			$html .= '<p class="ch-plan-card__fairuse">' . esc_html( $notice ) . '</p>';
		}

		$html .= '</article>';
	}

	$html .= '</div>';

	return $html;
}

/* -------------------------------------------------------------------------
 * curedhosting/comparison-table
 * ---------------------------------------------------------------------- */

/**
 * Render the plan comparison table. Same settings data as the cards.
 *
 * @param array $attributes Block attributes.
 * @return string
 */
function curedhosting_render_comparison_block( $attributes ) {
	unset( $attributes );

	$plans    = curedhosting_plans( 'all' );
	$settings = curedhosting_settings();
	$notice   = (string) curedhosting_setting( 'plans_fair_use_note' );

	if ( empty( $plans ) ) {
		return '';
	}

	$plan_ids = array_keys( $plans );

	$html  = '<div class="ch-table-wrap" role="region" aria-label="' . esc_attr__( 'Hosting plan comparison', 'curedhosting' ) . '" tabindex="0">';
	$html .= '<table class="ch-table">';
	$html .= '<caption class="screen-reader-text">' . esc_html__( 'Comparison of CuredHosting plans with exact limits', 'curedhosting' ) . '</caption>';

	/* Header row. */
	$html .= '<thead><tr><th scope="col">' . esc_html__( 'Limit / feature', 'curedhosting' ) . '</th>';
	foreach ( $plan_ids as $id ) {
		$html .= '<th scope="col">' . esc_html( (string) $plans[ $id ]['name'] ) . '</th>';
	}
	$html .= '</tr></thead>';

	/* Structured limit rows. */
	$html .= '<tbody>';
	foreach ( curedhosting_plan_limit_fields() as $field ) {
		$html .= '<tr><th scope="row">' . esc_html( $field['label'] ) . '</th>';
		foreach ( $plan_ids as $id ) {
			$value = isset( $plans[ $id ][ $field['key'] ] ) ? (string) $plans[ $id ][ $field['key'] ] : '';
			$html .= '<td>' . ( '' !== $value ? esc_html( $value ) : '<span aria-hidden="true">—</span>' ) . '</td>';
		}
		$html .= '</tr>';
	}

	/* WordPress preinstalled row. */
	$html .= '<tr><th scope="row">' . esc_html__( 'WordPress preinstalled', 'curedhosting' ) . '</th>';
	foreach ( $plan_ids as $id ) {
		$has_wp = ! empty( $plans[ $id ]['is_wordpress'] );
		$html  .= '<td>' . ( $has_wp
			? esc_html__( 'Yes', 'curedhosting' )
			: esc_html__( 'Not applicable', 'curedhosting' ) ) . '</td>';
	}
	$html .= '</tr>';

	/* Extras row. */
	$html .= '<tr><th scope="row">' . esc_html__( 'Also includes', 'curedhosting' ) . '</th>';
	foreach ( $plan_ids as $id ) {
		$extras = isset( $plans[ $id ]['extras'] ) && is_array( $plans[ $id ]['extras'] ) ? $plans[ $id ]['extras'] : array();
		$html  .= '<td>' . esc_html( implode( '; ', array_filter( array_map( 'trim', $extras ) ) ) ) . '</td>';
	}
	$html .= '</tr>';

	/* Best-for row. */
	$html .= '<tr><th scope="row">' . esc_html__( 'Best for', 'curedhosting' ) . '</th>';
	foreach ( $plan_ids as $id ) {
		$html .= '<td>' . esc_html( (string) ( isset( $plans[ $id ]['best_for'] ) ? $plans[ $id ]['best_for'] : '' ) ) . '</td>';
	}
	$html .= '</tr>';
	$html .= '</tbody>';

	/* CTA footer row. */
	$html .= '<tfoot><tr><th scope="row">' . esc_html__( 'Next step', 'curedhosting' ) . '</th>';
	foreach ( $plan_ids as $id ) {
		$cta_label = (string) ( isset( $plans[ $id ]['cta_label'] ) ? $plans[ $id ]['cta_label'] : '' );
		$cta_url   = (string) ( isset( $plans[ $id ]['cta_url'] ) ? $plans[ $id ]['cta_url'] : '' );
		if ( '' === $cta_label ) {
			$cta_label = (string) curedhosting_setting( 'plans_cta_label' );
		}
		if ( '' === $cta_url ) {
			$cta_url = (string) curedhosting_setting( 'plans_cta_url' );
		}
		$cta_url = curedhosting_resolve_url( $cta_url );
		$html   .= '<td>' . ( '' !== $cta_url
			? '<a href="' . esc_url( $cta_url ) . '">' . esc_html( $cta_label ) . '</a>'
			: '' ) . '</td>';
	}
	$html .= '</tr></tfoot>';

	$html .= '</table></div>';

	if ( '' !== $notice ) {
		$html .= '<p class="ch-table-note">' . esc_html( $notice ) . '</p>';
	}

	$html .= '<p class="ch-table-note">' . esc_html( (string) curedhosting_setting( 'plans_price_note' ) ) . ' ' .
		esc_html__( 'Checkout is never fully automated: we confirm eligibility, plan fit, and final terms with you before purchase.', 'curedhosting' ) . '</p>';

	unset( $settings );

	return $html;
}

/* -------------------------------------------------------------------------
 * curedhosting/faq
 * ---------------------------------------------------------------------- */

/**
 * Render the FAQ as native details/summary (keyboard accessible,
 * no JS required; theme.js adds an optional single-open behavior).
 *
 * @param array $attributes Block attributes.
 * @return string
 */
function curedhosting_render_faq_block( $attributes ) {
	unset( $attributes );

	$items = curedhosting_faq_items();
	if ( empty( $items ) ) {
		return '';
	}

	$html = '<div class="ch-faq" data-ch-accordion>';
	foreach ( $items as $item ) {
		$html .= '<details class="ch-faq__item">';
		$html .= '<summary>' . esc_html( $item['q'] ) . '</summary>';
		$html .= '<div class="ch-faq__answer"><p>' . nl2br( esc_html( $item['a'] ) ) . '</p></div>';
		$html .= '</details>';
	}
	$html .= '</div>';

	return $html;
}

/* -------------------------------------------------------------------------
 * curedhosting/promo
 * ---------------------------------------------------------------------- */

/**
 * Render the grand-opening section. Renders nothing when the admin
 * has switched the promotion off.
 *
 * @param array $attributes Block attributes.
 * @return string
 */
function curedhosting_render_promo_block( $attributes ) {
	unset( $attributes );

	if ( ! curedhosting_setting( 'promo_enabled' ) ) {
		return '';
	}

	$kicker    = (string) curedhosting_setting( 'promo_kicker' );
	$heading   = (string) curedhosting_setting( 'promo_heading' );
	$intro     = (string) curedhosting_setting( 'promo_intro' );
	$points    = (array) curedhosting_setting( 'promo_points', array() );
	$terms     = (string) curedhosting_setting( 'promo_terms' );
	$link_label = (string) curedhosting_setting( 'promo_link_label' );
	$link_url  = curedhosting_resolve_url( (string) curedhosting_setting( 'promo_link_url' ) );

	$html  = '<section class="ch-promo" aria-labelledby="ch-promo-heading">';
	$html .= '<div class="ch-promo__inner">';

	if ( '' !== $kicker ) {
		$html .= '<p class="ch-kicker">' . esc_html( $kicker ) . '</p>';
	}
	if ( '' !== $heading ) {
		$html .= '<h2 id="ch-promo-heading" class="ch-promo__heading">' . esc_html( $heading ) . '</h2>';
	}
	if ( '' !== $intro ) {
		$html .= '<p class="ch-promo__intro">' . esc_html( $intro ) . '</p>';
	}

	$points = array_filter( array_map( 'trim', array_map( 'strval', $points ) ) );
	if ( ! empty( $points ) ) {
		$html .= '<ul class="ch-promo__points">';
		foreach ( $points as $point ) {
			$html .= '<li>' . esc_html( $point ) . '</li>';
		}
		$html .= '</ul>';
	}

	if ( '' !== $terms ) {
		$html .= '<p class="ch-promo__terms">' . esc_html( $terms ) . '</p>';
	}

	if ( '' !== $link_url && '' !== $link_label ) {
		$html .= '<p class="ch-promo__link"><a href="' . esc_url( $link_url ) . '">' . esc_html( $link_label ) . '</a></p>';
	}

	$html .= '</div></section>';

	return $html;
}

/* -------------------------------------------------------------------------
 * curedhosting/support-summary
 * ---------------------------------------------------------------------- */

/**
 * Render contact details used in the support section and footer.
 *
 * variant=list:   contact rows only (support section).
 * variant=footer: tagline + contact rows + infrastructure note + legal lines.
 *
 * @param array $attributes Block attributes.
 * @return string
 */
function curedhosting_render_support_block( $attributes ) {
	$variant = ( isset( $attributes['variant'] ) && 'footer' === $attributes['variant'] ) ? 'footer' : 'list';

	$support_email = (string) curedhosting_setting( 'support_email' );
	$contact_email = (string) curedhosting_setting( 'contact_email' );
	$phone         = (string) curedhosting_setting( 'phone' );
	$hours         = (string) curedhosting_setting( 'hours' );
	$note          = (string) curedhosting_setting( 'location_note' );

	$rows = array();

	if ( '' !== $support_email ) {
		$rows[] = array(
			'label' => __( 'Support', 'curedhosting' ),
			'value' => '<a href="mailto:' . esc_attr( antispambot( $support_email ) ) . '">' . esc_html( antispambot( $support_email ) ) . '</a>',
		);
	}
	if ( '' !== $contact_email ) {
		$rows[] = array(
			'label' => __( 'General', 'curedhosting' ),
			'value' => '<a href="mailto:' . esc_attr( antispambot( $contact_email ) ) . '">' . esc_html( antispambot( $contact_email ) ) . '</a>',
		);
	}
	if ( '' !== $phone ) {
		$rows[] = array(
			'label' => __( 'Phone', 'curedhosting' ),
			'value' => esc_html( $phone ),
		);
	}
	if ( '' !== $hours ) {
		$rows[] = array(
			'label' => __( 'Hours', 'curedhosting' ),
			'value' => esc_html( $hours ),
		);
	}

	if ( 'footer' === $variant ) {
		$tagline    = (string) curedhosting_setting( 'footer_tagline' );
		$infra_note = (string) curedhosting_setting( 'footer_infra_note' );
		$legal      = (array) curedhosting_setting( 'footer_legal_lines', array() );

		$html = '';
		if ( '' !== $tagline ) {
			$html .= '<p class="ch-footer__tagline">' . esc_html( $tagline ) . '</p>';
		}

		/* Compact contact rows for the footer. */
		if ( ! empty( $rows ) ) {
			$html .= '<dl class="ch-support-summary ch-support-summary--footer">';
			foreach ( $rows as $row ) {
				$html .= '<div class="ch-support-summary__row">';
				$html .= '<dt>' . esc_html( $row['label'] ) . '</dt>';
				$html .= '<dd>' . wp_kses(
					$row['value'],
					array(
						'a' => array(
							'href' => array(),
						),
					)
				) . '</dd>';
				$html .= '</div>';
			}
			$html .= '</dl>';
		}

		if ( '' !== $infra_note ) {
			$html .= '<p class="ch-footer__infra">' . esc_html( $infra_note ) . '</p>';
		}

		$legal = array_filter( array_map( 'trim', array_map( 'strval', $legal ) ) );
		if ( ! empty( $legal ) ) {
			foreach ( $legal as $line ) {
				$html .= '<p class="ch-footer__legal-line">' . esc_html( $line ) . '</p>';
			}
		}

		return $html;
	}

	if ( empty( $rows ) ) {
		return '';
	}

	$html = '<dl class="ch-support-summary">';
	foreach ( $rows as $row ) {
		$html .= '<div class="ch-support-summary__row">';
		$html .= '<dt>' . esc_html( $row['label'] ) . '</dt>';
		// Values are fully escaped above; wp_kses keeps it tight anyway.
		$html .= '<dd>' . wp_kses(
			$row['value'],
			array(
				'a' => array(
					'href' => array(),
				),
			)
		) . '</dd>';
		$html .= '</div>';
	}
	$html .= '</dl>';

	if ( '' !== $note ) {
		$html .= '<p class="ch-support-summary__note">' . esc_html( $note ) . '</p>';
	}

	return $html;
}
