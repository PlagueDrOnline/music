<?php
/**
 * Title:       All Plans Section
 * Slug:        curedhosting/plans-all
 * Categories:  curedhosting
 * Description: All four plans with exact limits and a recommendation CTA.
 * Viewport width: 1400
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","backgroundColor":"sage","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|30","right":"var:preset|spacing|30"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-sage-background-color has-background" style="padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--30)">
	<!-- wp:heading {"level":2,"fontSize":"x-large"} -->
	<h2 class="wp-block-heading has-x-large-font-size">Four plans. Exact limits. No “unlimited.”</h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"textColor":"ink-soft"} -->
	<p class="has-ink-soft-color has-text-color">Clear numbers, fair-use rules that apply to everyone equally, and a human who will tell you which plan actually fits. Pricing is confirmed with you in writing before anything is billed.</p>
	<!-- /wp:paragraph -->

	<!-- wp:spacer {"height":"var:preset|spacing|40"} -->
	<div style="height:var(--wp--preset--spacing--40)" aria-hidden="true" class="wp-block-spacer"></div>
	<!-- /wp:spacer -->

	<!-- wp:curedhosting/plans {"group":"all"} /-->

	<!-- wp:spacer {"height":"var:preset|spacing|40"} -->
	<div style="height:var(--wp--preset--spacing--40)" aria-hidden="true" class="wp-block-spacer"></div>
	<!-- /wp:spacer -->

	<!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
	<div class="wp-block-group">
		<!-- wp:paragraph {"fontSize":"medium"} -->
		<p class="has-medium-font-size">Not sure which one fits your site? That’s what the conversation is for.</p>
		<!-- /wp:paragraph -->
		<!-- wp:buttons -->
		<div class="wp-block-buttons">
			<!-- wp:button {"backgroundColor":"pine","textColor":"cream"} -->
			<div class="wp-block-button"><a class="wp-block-button__link has-cream-color has-pine-background-color has-text-color has-background wp-element-button" href="/onboarding/">Request a recommendation</a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
