<?php
/**
 * Title:       Hero
 * Slug:        curedhosting/hero
 * Categories:  curedhosting
 * Description: Homepage hero with the core promise, primary CTA, and plans CTA.
 * Viewport width: 1400
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|60","left":"var:preset|spacing|30","right":"var:preset|spacing|30"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--30)">
	<!-- wp:paragraph {"className":"ch-kicker","textColor":"gold-deep","fontSize":"small","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.12em","fontWeight":"600"}}} -->
	<p class="ch-kicker has-gold-deep-color has-text-color has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase">Small by design · Twenty accounts, no more</p>
	<!-- /wp:paragraph -->

	<!-- wp:heading {"level":1,"fontSize":"huge"} -->
	<h1 class="wp-block-heading has-huge-font-size">The cure for the common hosting headache.</h1>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"fontSize":"medium","style":{"spacing":{"margin":{"top":"var:preset|spacing|30"}}}} -->
	<p class="has-medium-font-size" style="margin-top:var(--wp--preset--spacing--30)">CuredHosting pairs your website with a personal tech — one person who knows your setup, answers when you reach out, and keeps things quietly healthy. You run your business. We keep the lights on.</p>
	<!-- /wp:paragraph -->

	<!-- wp:spacer {"height":"var:preset|spacing|40"} -->
	<div style="height:var(--wp--preset--spacing--40)" aria-hidden="true" class="wp-block-spacer"></div>
	<!-- /wp:spacer -->

	<!-- wp:buttons -->
	<div class="wp-block-buttons">
		<!-- wp:button {"backgroundColor":"pine","textColor":"cream"} -->
		<div class="wp-block-button"><a class="wp-block-button__link has-cream-color has-pine-background-color has-text-color has-background wp-element-button" href="/onboarding/">Start with a conversation</a></div>
		<!-- /wp:button -->
		<!-- wp:button {"className":"is-style-outline","textColor":"pine","style":{"border":{"width":"2px"}},"borderColor":"pine"} -->
		<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-pine-color has-text-color has-pine-border-color has-border-color wp-element-button" style="border-width:2px" href="/hosting-comparison/">See plans &amp; exact limits</a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->

	<!-- wp:paragraph {"fontSize":"small","textColor":"ink-soft","style":{"spacing":{"margin":{"top":"var:preset|spacing|30"}}}} -->
	<p class="has-ink-soft-color has-text-color has-small-font-size" style="margin-top:var(--wp--preset--spacing--30)">No automated checkout. Every account starts with a short conversation — and every plan lists its real limits, because “unlimited” was never one of ours.</p>
	<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
