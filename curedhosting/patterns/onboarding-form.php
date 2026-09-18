<?php
/**
 * Title:       Onboarding Questionnaire Form
 * Slug:        curedhosting/onboarding-form
 * Categories:  curedhosting
 * Description: The full onboarding questionnaire, handled natively by the theme.
 * Viewport width: 1400
 *
 * NOTE: Rendered LIVE when referenced through a Pattern block (the theme's
 * page setup does this automatically). For manual placement, insert it via
 * a Pattern block so the nonce stays fresh on every page load.
 */

defined( 'ABSPATH' ) || exit;

$ch_state   = curedhosting_form_state( 'onboarding' );
$ch_success = curedhosting_form_success( 'onboarding' );
$ch_errors  = isset( $ch_state['errors'] ) ? $ch_state['errors'] : array();

/**
 * Local helper: render a radio group for this form.
 * Guarded so a double include in one request can never fatal.
 *
 * @param string $key     Field key.
 * @param array  $options value => label.
 * @param array  $state   Form state.
 * @return void
 */
if ( ! function_exists( 'ch_ob_radio' ) ) {
	function ch_ob_radio( $key, $options, $state ) {
		foreach ( $options as $value => $label ) {
			printf(
				'<label class="ch-choice"><input type="radio" name="%1$s" value="%2$s"%3$s /> <span>%4$s</span></label>',
				esc_attr( $key ),
				esc_attr( $value ),
				curedhosting_field_checked( $state, $key, $value ), // Safe: static string ' checked' or ''.
				esc_html( $label )
			);
		}
	}
}
?>
<!-- wp:paragraph {"fontSize":"medium","textColor":"ink-soft"} -->
<p class="has-ink-soft-color has-text-color has-medium-font-size">These are the questions a good tech would ask before recommending anything. Answer what you can — “not sure yet” is a perfectly good answer for most of them.</p>
<!-- /wp:paragraph -->

<!-- wp:group {"backgroundColor":"sage","style":{"border":{"radius":"14px"},"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"layout":{"type":"constrained","justifyContent":"left"}} -->
<div class="wp-block-group has-sage-background-color has-background" style="border-radius:14px;padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--40)">
	<!-- wp:paragraph {"fontSize":"medium"} -->
	<p class="has-medium-font-size"><strong>Submitting this form requests a conversation — it is not a purchase.</strong> Nothing is billed, no account is created, and nothing here commits you to anything. Your answers stay on this website.</p>
	<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:spacer {"height":"var:preset|spacing|40"} -->
<div style="height:var(--wp--preset--spacing--40)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<?php if ( $ch_success ) : ?>
<div class="ch-form-success" role="status">
	<p><strong><?php echo esc_html__( 'Questionnaire received — thank you.', 'curedhosting' ); ?></strong>
	<?php echo esc_html__( 'We’ll read it properly and reply with an honest recommendation, usually within one business day. If the grand-opening offer applies to you, we’ll spell out the exact terms in that reply.', 'curedhosting' ); ?></p>
</div>
<?php endif; ?>

<form id="ch-form-onboarding" class="ch-form ch-form--wide" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
	<input type="hidden" name="action" value="ch_onboarding" />
	<?php wp_nonce_field( 'ch_onboarding_form', 'ch_nonce' ); ?>

	<p class="ch-hp" aria-hidden="true">
		<label><?php echo esc_html__( 'Leave this field empty', 'curedhosting' ); ?>
			<input type="text" name="ch_hp" value="" tabindex="-1" autocomplete="off" />
		</label>
	</p>

	<?php if ( ! empty( $ch_errors ) ) : ?>
		<div class="ch-form-errors" role="alert" tabindex="-1">
			<p class="ch-form-errors__title"><?php echo esc_html__( 'A few things need attention before you send this:', 'curedhosting' ); ?></p>
			<ul>
				<?php foreach ( $ch_errors as $ch_key => $ch_message ) : ?>
					<li><a href="#ch-ob-field-<?php echo esc_attr( $ch_key ); ?>"><?php echo esc_html( $ch_message ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

	<fieldset class="ch-fieldset">
		<legend><?php echo esc_html__( 'About you', 'curedhosting' ); ?></legend>

		<div class="ch-field">
			<label for="ch-ob-field-contact_name"><?php echo esc_html__( 'Your name', 'curedhosting' ); ?> <span class="ch-req" aria-hidden="true">*</span></label>
			<input type="text" id="ch-ob-field-contact_name" name="contact_name" required aria-required="true" autocomplete="name" maxlength="200"
				value="<?php echo curedhosting_field_value( $ch_state, 'contact_name' ); ?>"
				<?php echo curedhosting_field_error( $ch_state, 'contact_name' ) ? 'aria-invalid="true" aria-describedby="ch-ob-err-contact_name"' : ''; ?> />
			<?php if ( curedhosting_field_error( $ch_state, 'contact_name' ) ) : ?>
				<p class="ch-field-error" id="ch-ob-err-contact_name"><?php echo esc_html( curedhosting_field_error( $ch_state, 'contact_name' ) ); ?></p>
			<?php endif; ?>
		</div>

		<div class="ch-field">
			<label for="ch-ob-field-contact_email"><?php echo esc_html__( 'Email address', 'curedhosting' ); ?> <span class="ch-req" aria-hidden="true">*</span></label>
			<input type="email" id="ch-ob-field-contact_email" name="contact_email" required aria-required="true" autocomplete="email" maxlength="200"
				value="<?php echo curedhosting_field_value( $ch_state, 'contact_email' ); ?>"
				<?php echo curedhosting_field_error( $ch_state, 'contact_email' ) ? 'aria-invalid="true" aria-describedby="ch-ob-err-contact_email"' : ''; ?> />
			<?php if ( curedhosting_field_error( $ch_state, 'contact_email' ) ) : ?>
				<p class="ch-field-error" id="ch-ob-err-contact_email"><?php echo esc_html( curedhosting_field_error( $ch_state, 'contact_email' ) ); ?></p>
			<?php endif; ?>
		</div>
	</fieldset>

	<fieldset class="ch-fieldset">
		<legend><?php echo esc_html__( 'Your domains and current setup', 'curedhosting' ); ?></legend>

		<div class="ch-field">
			<label for="ch-ob-field-domain"><?php echo esc_html__( 'Your domain (current or planned)', 'curedhosting' ); ?> <span class="ch-req" aria-hidden="true">*</span></label>
			<input type="text" id="ch-ob-field-domain" name="domain" required aria-required="true" maxlength="200" placeholder="example.com"
				value="<?php echo curedhosting_field_value( $ch_state, 'domain' ); ?>"
				<?php echo curedhosting_field_error( $ch_state, 'domain' ) ? 'aria-invalid="true" aria-describedby="ch-ob-err-domain"' : ''; ?> />
			<p class="ch-field-hint"><?php echo esc_html__( 'No domain yet? Write the name you’re thinking of, or “not decided”.', 'curedhosting' ); ?></p>
			<?php if ( curedhosting_field_error( $ch_state, 'domain' ) ) : ?>
				<p class="ch-field-error" id="ch-ob-err-domain"><?php echo esc_html( curedhosting_field_error( $ch_state, 'domain' ) ); ?></p>
			<?php endif; ?>
		</div>

		<div class="ch-field">
			<label for="ch-ob-field-current_host"><?php echo esc_html__( 'Current host (if any)', 'curedhosting' ); ?></label>
			<input type="text" id="ch-ob-field-current_host" name="current_host" maxlength="200"
				value="<?php echo curedhosting_field_value( $ch_state, 'current_host' ); ?>" />
		</div>

		<div class="ch-field">
			<label for="ch-ob-field-registrar"><?php echo esc_html__( 'Domain registrar', 'curedhosting' ); ?></label>
			<input type="text" id="ch-ob-field-registrar" name="registrar" maxlength="200" placeholder="e.g. Namecheap, GoDaddy, Cloudflare"
				value="<?php echo curedhosting_field_value( $ch_state, 'registrar' ); ?>" />
		</div>

		<div class="ch-field">
			<label for="ch-ob-field-dns_provider"><?php echo esc_html__( 'DNS provider (if different from registrar)', 'curedhosting' ); ?></label>
			<input type="text" id="ch-ob-field-dns_provider" name="dns_provider" maxlength="200"
				value="<?php echo curedhosting_field_value( $ch_state, 'dns_provider' ); ?>" />
		</div>
	</fieldset>

	<fieldset class="ch-fieldset">
		<legend><?php echo esc_html__( 'Hosting preference', 'curedhosting' ); ?></legend>
		<div class="ch-field">
			<?php
			ch_ob_radio(
				'hosting_preference',
				array(
					'wordpress' => __( 'WordPress hosting', 'curedhosting' ),
					'linux'     => __( 'Linux hosting', 'curedhosting' ),
					'unsure'    => __( 'Not sure yet — recommend one', 'curedhosting' ),
				),
				$ch_state
			);
			?>
		</div>
	</fieldset>

	<fieldset class="ch-fieldset">
		<legend><?php echo esc_html__( 'What the account needs to hold', 'curedhosting' ); ?></legend>

		<div class="ch-field-row">
			<div class="ch-field">
				<label for="ch-ob-field-sites_count"><?php echo esc_html__( 'Number of sites', 'curedhosting' ); ?></label>
				<input type="number" min="0" max="9999" inputmode="numeric" id="ch-ob-field-sites_count" name="sites_count"
					value="<?php echo curedhosting_field_value( $ch_state, 'sites_count' ); ?>" />
			</div>
			<div class="ch-field">
				<label for="ch-ob-field-databases_count"><?php echo esc_html__( 'MySQL databases', 'curedhosting' ); ?></label>
				<input type="number" min="0" max="9999" inputmode="numeric" id="ch-ob-field-databases_count" name="databases_count"
					value="<?php echo curedhosting_field_value( $ch_state, 'databases_count' ); ?>" />
			</div>
			<div class="ch-field">
				<label for="ch-ob-field-subdomains_count"><?php echo esc_html__( 'Subdomains', 'curedhosting' ); ?></label>
				<input type="number" min="0" max="9999" inputmode="numeric" id="ch-ob-field-subdomains_count" name="subdomains_count"
					value="<?php echo curedhosting_field_value( $ch_state, 'subdomains_count' ); ?>" />
			</div>
		</div>

		<div class="ch-field">
			<p class="ch-field-label"><?php echo esc_html__( 'Do you need a staging setup?', 'curedhosting' ); ?></p>
			<?php
			ch_ob_radio(
				'staging',
				array(
					'yes'    => __( 'Yes — we test before shipping', 'curedhosting' ),
					'no'     => __( 'No, not right now', 'curedhosting' ),
					'unsure' => __( 'Not sure what that means — explain it to us', 'curedhosting' ),
				),
				$ch_state
			);
			?>
		</div>

		<div class="ch-field-row">
			<div class="ch-field">
				<label for="ch-ob-field-storage_estimate"><?php echo esc_html__( 'Approximate storage', 'curedhosting' ); ?></label>
				<select id="ch-ob-field-storage_estimate" name="storage_estimate">
					<option value="under-5gb"<?php echo curedhosting_field_selected( $ch_state, 'storage_estimate', 'under-5gb' ); ?>><?php echo esc_html__( 'Under 5 GB', 'curedhosting' ); ?></option>
					<option value="5-10gb"<?php echo curedhosting_field_selected( $ch_state, 'storage_estimate', '5-10gb' ); ?>><?php echo esc_html__( '5–10 GB', 'curedhosting' ); ?></option>
					<option value="10-20gb"<?php echo curedhosting_field_selected( $ch_state, 'storage_estimate', '10-20gb' ); ?>><?php echo esc_html__( '10–20 GB', 'curedhosting' ); ?></option>
					<option value="over-20gb"<?php echo curedhosting_field_selected( $ch_state, 'storage_estimate', 'over-20gb' ); ?>><?php echo esc_html__( 'Over 20 GB', 'curedhosting' ); ?></option>
					<option value="unsure"<?php echo curedhosting_field_selected( $ch_state, 'storage_estimate', 'unsure' ); ?>><?php echo esc_html__( 'Not sure', 'curedhosting' ); ?></option>
				</select>
			</div>
			<div class="ch-field">
				<label for="ch-ob-field-traffic_estimate"><?php echo esc_html__( 'Approximate monthly visits', 'curedhosting' ); ?></label>
				<select id="ch-ob-field-traffic_estimate" name="traffic_estimate">
					<option value="under-1k"<?php echo curedhosting_field_selected( $ch_state, 'traffic_estimate', 'under-1k' ); ?>><?php echo esc_html__( 'Under 1,000', 'curedhosting' ); ?></option>
					<option value="1k-10k"<?php echo curedhosting_field_selected( $ch_state, 'traffic_estimate', '1k-10k' ); ?>><?php echo esc_html__( '1,000–10,000', 'curedhosting' ); ?></option>
					<option value="10k-50k"<?php echo curedhosting_field_selected( $ch_state, 'traffic_estimate', '10k-50k' ); ?>><?php echo esc_html__( '10,000–50,000', 'curedhosting' ); ?></option>
					<option value="over-50k"<?php echo curedhosting_field_selected( $ch_state, 'traffic_estimate', 'over-50k' ); ?>><?php echo esc_html__( 'Over 50,000', 'curedhosting' ); ?></option>
					<option value="unsure"<?php echo curedhosting_field_selected( $ch_state, 'traffic_estimate', 'unsure' ); ?>><?php echo esc_html__( 'Not sure', 'curedhosting' ); ?></option>
				</select>
			</div>
		</div>
	</fieldset>

	<fieldset class="ch-fieldset">
		<legend><?php echo esc_html__( 'Email', 'curedhosting' ); ?></legend>
		<div class="ch-field">
			<label for="ch-ob-field-email_needs"><?php echo esc_html__( 'Email requirements', 'curedhosting' ); ?></label>
			<textarea id="ch-ob-field-email_needs" name="email_needs" rows="3" maxlength="2000" placeholder="How many mailboxes? Any forwarding or mailing-list needs?"><?php echo esc_textarea( isset( $ch_state['values']['email_needs'] ) ? $ch_state['values']['email_needs'] : '' ); ?></textarea>
			<p class="ch-field-hint"><?php echo esc_html__( 'Note: moving existing mailbox data from another provider needs separate review — each plan includes a set number of fresh mailboxes.', 'curedhosting' ); ?></p>
		</div>
	</fieldset>

	<fieldset class="ch-fieldset">
		<legend><?php echo esc_html__( 'Migration', 'curedhosting' ); ?></legend>
		<div class="ch-field">
			<p class="ch-field-label"><?php echo esc_html__( 'Is there an existing site to move?', 'curedhosting' ); ?></p>
			<?php
			ch_ob_radio(
				'migration',
				array(
					'yes'      => __( 'Yes — migrate my existing site (standard WordPress migrations are included)', 'curedhosting' ),
					'new-site' => __( 'No — this is a brand-new site', 'curedhosting' ),
					'unsure'   => __( 'Not sure — let’s look together', 'curedhosting' ),
				),
				$ch_state
			);
			?>
		</div>
		<div class="ch-field">
			<label for="ch-ob-field-migration_details"><?php echo esc_html__( 'Anything unusual about the move?', 'curedhosting' ); ?></label>
			<textarea id="ch-ob-field-migration_details" name="migration_details" rows="3" maxlength="2000" placeholder="Redesign plans, compromised site history, large media libraries, unusual stacks — anything we should know."><?php echo esc_textarea( isset( $ch_state['values']['migration_details'] ) ? $ch_state['values']['migration_details'] : '' ); ?></textarea>
			<p class="ch-field-hint"><?php echo esc_html__( 'Redesigns, compromised-site remediation, mailbox migration, and unusual reconstruction are scoped separately — we’ll confirm before anything is committed.', 'curedhosting' ); ?></p>
		</div>
	</fieldset>

	<fieldset class="ch-fieldset">
		<legend><?php echo esc_html__( 'Current problems', 'curedhosting' ); ?></legend>
		<div class="ch-field">
			<label for="ch-ob-field-current_problems"><?php echo esc_html__( 'What’s giving you a hosting headache right now?', 'curedhosting' ); ?></label>
			<textarea id="ch-ob-field-current_problems" name="current_problems" rows="3" maxlength="2000" placeholder="Slow support, surprise charges, outages, confusing dashboards…"><?php echo esc_textarea( isset( $ch_state['values']['current_problems'] ) ? $ch_state['values']['current_problems'] : '' ); ?></textarea>
		</div>
	</fieldset>

	<fieldset class="ch-fieldset">
		<legend><?php echo esc_html__( 'Timing', 'curedhosting' ); ?></legend>
		<div class="ch-field">
			<p class="ch-field-label"><?php echo esc_html__( 'When would you like to launch or move?', 'curedhosting' ); ?></p>
			<?php
			ch_ob_radio(
				'launch_timing',
				array(
					'asap'        => __( 'As soon as sensibly possible', 'curedhosting' ),
					'2-4-weeks'   => __( 'Within 2–4 weeks', 'curedhosting' ),
					'1-3-months'  => __( 'In 1–3 months', 'curedhosting' ),
					'flexible'    => __( 'Flexible — no rush', 'curedhosting' ),
				),
				$ch_state
			);
			?>
		</div>
	</fieldset>

	<fieldset class="ch-fieldset">
		<legend><?php echo esc_html__( 'Permission', 'curedhosting' ); ?></legend>
		<div class="ch-field ch-field--consent">
			<label class="ch-consent">
				<input type="checkbox" name="consent" value="yes" required aria-required="true"
					<?php echo curedhosting_field_checked( $ch_state, 'consent', 'yes' ); ?>
					<?php echo curedhosting_field_error( $ch_state, 'consent' ) ? 'aria-invalid="true" aria-describedby="ch-ob-err-consent"' : ''; ?> />
				<span><?php echo esc_html__( 'CuredHosting may contact me by email about this questionnaire and the recommendation that follows. No newsletters, no marketing lists — just this conversation.', 'curedhosting' ); ?> <span class="ch-req" aria-hidden="true">*</span></span>
			</label>
			<?php if ( curedhosting_field_error( $ch_state, 'consent' ) ) : ?>
				<p class="ch-field-error" id="ch-ob-err-consent"><?php echo esc_html( curedhosting_field_error( $ch_state, 'consent' ) ); ?></p>
			<?php endif; ?>
		</div>
	</fieldset>

	<p class="ch-form-notice"><?php echo esc_html__( 'Reminder: sending this questionnaire requests a conversation, not a purchase. We reply with a recommendation and written terms; nothing is billed until you approve them.', 'curedhosting' ); ?></p>

	<p>
		<button type="submit" class="ch-btn ch-btn--primary"><?php echo esc_html__( 'Send questionnaire', 'curedhosting' ); ?></button>
	</p>
</form>
