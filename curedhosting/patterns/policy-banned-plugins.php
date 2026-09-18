<?php
/**
 * Title:       Policy: Restricted WordPress Plugins
 * Slug:        curedhosting/policy-banned-plugins
 * Categories:  curedhosting
 * Description: Default restricted-plugin policy content. Edit freely.
 * Viewport width: 1400
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:paragraph {"fontSize":"medium","textColor":"ink-soft"} -->
<p class="has-ink-soft-color has-text-color has-medium-font-size">Because CuredHosting accounts share carefully managed infrastructure, some WordPress plugins are restricted. This isn’t gatekeeping — it’s the same neighbor-protection principle as our <a href="/fair-use/">fair-use policy</a>, applied to code.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2,"fontSize":"x-large"} -->
<h2 class="wp-block-heading has-x-large-font-size">How we decide</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>A plugin is restricted when it reliably misbehaves on shared hosting: sustained heavy CPU or I/O, runaway scheduled tasks, enormous on-server storage growth, or behavior that endangers neighboring accounts. We evaluate plugins case by case, and this list is indicative rather than exhaustive.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2,"fontSize":"x-large"} -->
<h2 class="wp-block-heading has-x-large-font-size">Categories we restrict</h2>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
	<li><strong>On-server bulk backup plugins</strong> that store large archives inside the hosting account instead of sending them off-site. We’ll help you configure off-site backups properly.</li>
	<li><strong>Aggressive scanners</strong> (security or SEO) configured for frequent full-site crawls that pin CPU.</li>
	<li><strong>Bulk image processors</strong> that reprocess entire media libraries server-side on schedule.</li>
	<li><strong>Newsletter plugins sending large volumes</strong> of mail through the hosting account’s mail server rather than a dedicated sending service.</li>
	<li><strong>Crypto-currency miners and monetization scripts</strong> that consume visitor or server resources.</li>
	<li><strong>Plugins requiring persistent daemons, custom server modules, or root-level system access</strong> that reseller environments cannot safely provide.</li>
	<li><strong>Nulled, pirated, or unofficially distributed premium plugins</strong> — always, no exceptions. These are the most common source of compromised sites.</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":2,"fontSize":"x-large"} -->
<h2 class="wp-block-heading has-x-large-font-size">What happens if you need one</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Tell us what you’re trying to achieve. Usually there is a lighter plugin, a better configuration, or a dedicated service that does the job without risking your neighbors. If a plugin you rely on becomes restricted, we’ll talk it through with you before asking for any change.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2,"fontSize":"x-large"} -->
<h2 class="wp-block-heading has-x-large-font-size">Our promise in return</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>We won’t restrict plugins to sell you an upgrade, and we won’t invent restrictions. If a plugin is limited, we’ll explain the specific resource behavior behind the decision — and show you the numbers when we have them.</p>
<!-- /wp:paragraph -->
