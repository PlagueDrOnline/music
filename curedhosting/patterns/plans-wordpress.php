<?php
/**
 * Title:       WordPress Hosting Page
 * Slug:        curedhosting/plans-wordpress
 * Categories:  curedhosting
 * Description: WordPress hosting page body: intro, plan cards, fair-use note, CTA.
 * Viewport width: 1400
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:paragraph {"fontSize":"medium","textColor":"ink-soft"} -->
<p class="has-ink-soft-color has-text-color has-medium-font-size">WordPress comes preinstalled, updated, and watched over. Two plans, exact limits, and standard migration included — so moving an existing site is part of the deal, not an upsell.</p>
<!-- /wp:paragraph -->

<!-- wp:spacer {"height":"var:preset|spacing|40"} -->
<div style="height:var(--wp--preset--spacing--40)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:curedhosting/plans {"group":"wordpress"} /-->

<!-- wp:spacer {"height":"var:preset|spacing|40"} -->
<div style="height:var(--wp--preset--spacing--40)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:group {"backgroundColor":"paper","style":{"border":{"width":"1px","color":"var:preset|color|line","radius":"14px"},"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"layout":{"type":"constrained","justifyContent":"left"}} -->
<div class="wp-block-group has-border-color has-paper-background-color has-background" style="border-color:var(--wp--preset--color--line);border-width:1px;border-radius:14px;padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--40)">
	<!-- wp:paragraph {"fontSize":"medium"} -->
	<p class="has-medium-font-size"><strong>What happens after you pick a plan:</strong> we confirm the terms with you in writing, set up your cPanel account, migrate your site if you have one, and verify SSL, permalinks, and launch checks together. See <a href="/free-migration/">what’s included in migration</a> and <a href="/restricted-plugins/">which plugins we ask you to avoid</a>.</p>
	<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
