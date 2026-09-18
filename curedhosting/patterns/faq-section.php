<?php
/**
 * Title:       FAQ Section
 * Slug:        curedhosting/faq-section
 * Categories:  curedhosting
 * Description: FAQ heading plus the settings-driven FAQ accordion.
 * Viewport width: 1400
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","backgroundColor":"sage","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|30","right":"var:preset|spacing|30"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-sage-background-color has-background" style="padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--30)">
	<!-- wp:heading {"level":2,"fontSize":"x-large"} -->
	<h2 class="wp-block-heading has-x-large-font-size">Questions, answered plainly</h2>
	<!-- /wp:heading -->

	<!-- wp:spacer {"height":"var:preset|spacing|30"} -->
	<div style="height:var(--wp--preset--spacing--30)" aria-hidden="true" class="wp-block-spacer"></div>
	<!-- /wp:spacer -->

	<!-- wp:curedhosting/faq /-->

	<!-- wp:paragraph {"textColor":"ink-soft","style":{"spacing":{"margin":{"top":"var:preset|spacing|30"}}}} -->
	<p class="has-ink-soft-color has-text-color" style="margin-top:var(--wp--preset--spacing--30)">Something we didn’t cover? <a href="/contact/">Ask us directly</a> — you’ll get an answer from the person who would actually run your account.</p>
	<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
