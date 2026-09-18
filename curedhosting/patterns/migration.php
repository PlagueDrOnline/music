<?php
/**
 * Title:       Free Migration
 * Slug:        curedhosting/migration
 * Categories:  curedhosting
 * Description: What standard WordPress migrations include, and what needs separate scoping.
 * Viewport width: 1400
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|30","right":"var:preset|spacing|30"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--30)">
	<!-- wp:paragraph {"className":"ch-kicker","textColor":"gold-deep","fontSize":"small","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.12em","fontWeight":"600"}}} -->
	<p class="ch-kicker has-gold-deep-color has-text-color has-small-font-size" style="font-weight:600;letter-spacing:0.12em;text-transform:uppercase">Moving to CuredHosting</p>
	<!-- /wp:paragraph -->

	<!-- wp:heading {"level":2,"fontSize":"x-large"} -->
	<h2 class="wp-block-heading has-x-large-font-size">Free standard WordPress migration — done by a person</h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"textColor":"ink-soft"} -->
	<p class="has-ink-soft-color has-text-color">Most hosts say “free migration” and mean “run this plugin and good luck.” Ours is a careful, human move with checks at the end. Every migration starts with a review conversation, so scope is confirmed before we touch anything.</p>
	<!-- /wp:paragraph -->

	<!-- wp:spacer {"height":"var:preset|spacing|40"} -->
	<div style="height:var(--wp--preset--spacing--40)" aria-hidden="true" class="wp-block-spacer"></div>
	<!-- /wp:spacer -->

	<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"var:preset|spacing|40"}}}} -->
	<div class="wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"backgroundColor":"paper","style":{"border":{"width":"1px","color":"var:preset|color|line","radius":"14px"},"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"layout":{"type":"constrained","justifyContent":"left"}} -->
			<div class="wp-block-group has-border-color has-paper-background-color has-background" style="border-color:var(--wp--preset--color--line);border-width:1px;border-radius:14px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)">
				<!-- wp:heading {"level":3,"fontSize":"large"} -->
				<h3 class="wp-block-heading has-large-font-size">Included in every standard migration</h3>
				<!-- /wp:heading -->
				<!-- wp:list {"className":"ch-checklist"} -->
				<ul class="ch-checklist">
					<li>WordPress core files</li>
					<li>Uploads and media library</li>
					<li>Database</li>
					<li>Configuration review (wp-config, permissions)</li>
					<li>Basic compatibility and permalink checks</li>
					<li>SSL and launch verification</li>
					<li>DNS guidance — we walk you through it; you keep registrar access</li>
				</ul>
				<!-- /wp:list -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"backgroundColor":"paper","style":{"border":{"width":"1px","color":"var:preset|color|line","radius":"14px"},"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"layout":{"type":"constrained","justifyContent":"left"}} -->
			<div class="wp-block-group has-border-color has-paper-background-color has-background" style="border-color:var(--wp--preset--color--line);border-width:1px;border-radius:14px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)">
				<!-- wp:heading {"level":3,"fontSize":"large"} -->
				<h3 class="wp-block-heading has-large-font-size">Scoped separately, with your go-ahead first</h3>
				<!-- /wp:heading -->
				<!-- wp:list {"className":"ch-scope-list"} -->
				<ul class="ch-scope-list">
					<li>Redesigns and custom development</li>
					<li>Compromised-site remediation</li>
					<li>Email mailbox migration</li>
					<li>Unusual reconstruction (custom frameworks, non-standard stacks)</li>
					<li>Anything needing coordination beyond DNS with third parties</li>
				</ul>
				<!-- /wp:list -->
				<!-- wp:paragraph {"fontSize":"small","textColor":"ink-soft"} -->
				<p class="has-ink-soft-color has-text-color has-small-font-size">If your move needs any of these, we’ll tell you plainly what’s involved and what it costs before committing to anything.</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
