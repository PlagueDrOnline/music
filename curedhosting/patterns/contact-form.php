<?php
/**
 * Title:       Contact & Support Form
 * Slug:        curedhosting/contact-form
 * Categories:  curedhosting
 * Description: Accessible contact form handled natively by the theme.
 * Viewport width: 1400
 *
 * NOTE: This pattern is rendered LIVE when pages reference it with a
 * Pattern block (the default the theme's page setup uses). Inserting it
 * directly from the inserter bakes a stale nonce into the page — use the
 * Pattern block instead for any manual placement.
 */

defined( 'ABSPATH' ) || exit;

$ch_state   = curedhosting_form_state( 'contact' );
$ch_success = curedhosting_form_success( 'contact' );
$ch_errors  = isset( $ch_state['errors'] ) ? $ch_state['errors'] : array();
?>
<!-- wp:paragraph {"fontSize":"medium","textColor":"ink-soft"} -->
<p class="has-ink-soft-color has-text-color has-medium-font-size">Write to us the way you’d talk to a person. No ticket numbers, no auto-replies pretending otherwise — a human reads this and writes back.</p>
<!-- /wp:paragraph -->

<!-- wp:curedhosting/support-summary /-->

<!-- wp:spacer {"height":"var:preset|spacing|40"} -->
<div style="height:var(--wp--preset--spacing--40)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<?php if ( $ch_success ) : ?>
<div class="ch-form-success" role="status">
	<p><strong><?php echo esc_html__( 'Message sent. Thank you.', 'curedhosting' ); ?></strong>
	<?php echo esc_html__( 'It has landed with a real person, who will reply within one business day — usually much sooner.', 'curedhosting' ); ?></p>
</div>
<?php endif; ?>

<form id="ch-form-contact" class="ch-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
	<input type="hidden" name="action" value="ch_contact" />
	<?php wp_nonce_field( 'ch_contact_form', 'ch_nonce' ); ?>

	<p class="ch-hp" aria-hidden="true">
		<label><?php echo esc_html__( 'Leave this field empty', 'curedhosting' ); ?>
			<input type="text" name="ch_hp" value="" tabindex="-1" autocomplete="off" />
		</label>
	</p>

	<?php if ( ! empty( $ch_errors ) ) : ?>
		<div class="ch-form-errors" role="alert" tabindex="-1">
			<p class="ch-form-errors__title"><?php echo esc_html__( 'Please check the following before sending:', 'curedhosting' ); ?></p>
			<ul>
				<?php foreach ( $ch_errors as $ch_key => $ch_message ) : ?>
					<li><a href="#ch-contact-field-<?php echo esc_attr( $ch_key ); ?>"><?php echo esc_html( $ch_message ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

	<div class="ch-field">
		<label for="ch-contact-field-contact_name"><?php echo esc_html__( 'Your name', 'curedhosting' ); ?> <span class="ch-req" aria-hidden="true">*</span></label>
		<input type="text" id="ch-contact-field-contact_name" name="contact_name" required aria-required="true" autocomplete="name" maxlength="200"
			value="<?php echo curedhosting_field_value( $ch_state, 'contact_name' ); // Already escaped. ?>"
			<?php echo curedhosting_field_error( $ch_state, 'contact_name' ) ? 'aria-invalid="true" aria-describedby="ch-contact-err-contact_name"' : ''; ?> />
		<?php if ( curedhosting_field_error( $ch_state, 'contact_name' ) ) : ?>
			<p class="ch-field-error" id="ch-contact-err-contact_name"><?php echo esc_html( curedhosting_field_error( $ch_state, 'contact_name' ) ); ?></p>
		<?php endif; ?>
	</div>

	<div class="ch-field">
		<label for="ch-contact-field-contact_email"><?php echo esc_html__( 'Email address', 'curedhosting' ); ?> <span class="ch-req" aria-hidden="true">*</span></label>
		<input type="email" id="ch-contact-field-contact_email" name="contact_email" required aria-required="true" autocomplete="email" maxlength="200"
			value="<?php echo curedhosting_field_value( $ch_state, 'contact_email' ); // Already escaped. ?>"
			<?php echo curedhosting_field_error( $ch_state, 'contact_email' ) ? 'aria-invalid="true" aria-describedby="ch-contact-err-contact_email"' : ''; ?> />
		<p class="ch-field-hint"><?php echo esc_html__( 'We only use this to reply to you. It is never shared.', 'curedhosting' ); ?></p>
		<?php if ( curedhosting_field_error( $ch_state, 'contact_email' ) ) : ?>
			<p class="ch-field-error" id="ch-contact-err-contact_email"><?php echo esc_html( curedhosting_field_error( $ch_state, 'contact_email' ) ); ?></p>
		<?php endif; ?>
	</div>

	<div class="ch-field">
		<label for="ch-contact-field-topic"><?php echo esc_html__( 'What is this about?', 'curedhosting' ); ?></label>
		<select id="ch-contact-field-topic" name="topic">
			<option value="general"<?php echo curedhosting_field_selected( $ch_state, 'topic', 'general' ); ?>><?php echo esc_html__( 'A general question', 'curedhosting' ); ?></option>
			<option value="support"<?php echo curedhosting_field_selected( $ch_state, 'topic', 'support' ); ?>><?php echo esc_html__( 'Support for an existing site', 'curedhosting' ); ?></option>
			<option value="migration"<?php echo curedhosting_field_selected( $ch_state, 'topic', 'migration' ); ?>><?php echo esc_html__( 'Migrating a site to CuredHosting', 'curedhosting' ); ?></option>
			<option value="grand-opening"<?php echo curedhosting_field_selected( $ch_state, 'topic', 'grand-opening' ); ?>><?php echo esc_html__( 'The grand-opening offer', 'curedhosting' ); ?></option>
			<option value="other"<?php echo curedhosting_field_selected( $ch_state, 'topic', 'other' ); ?>><?php echo esc_html__( 'Something else', 'curedhosting' ); ?></option>
		</select>
	</div>

	<div class="ch-field">
		<label for="ch-contact-field-message"><?php echo esc_html__( 'Your message', 'curedhosting' ); ?> <span class="ch-req" aria-hidden="true">*</span></label>
		<textarea id="ch-contact-field-message" name="message" rows="6" required aria-required="true" maxlength="5000"
			<?php echo curedhosting_field_error( $ch_state, 'message' ) ? 'aria-invalid="true" aria-describedby="ch-contact-err-message"' : ''; ?>><?php echo esc_textarea( isset( $ch_state['values']['message'] ) ? $ch_state['values']['message'] : '' ); ?></textarea>
		<?php if ( curedhosting_field_error( $ch_state, 'message' ) ) : ?>
			<p class="ch-field-error" id="ch-contact-err-message"><?php echo esc_html( curedhosting_field_error( $ch_state, 'message' ) ); ?></p>
		<?php endif; ?>
	</div>

	<div class="ch-field ch-field--consent">
		<label class="ch-consent">
			<input type="checkbox" name="consent" value="yes" required aria-required="true"
				<?php echo curedhosting_field_checked( $ch_state, 'consent', 'yes' ); ?>
				<?php echo curedhosting_field_error( $ch_state, 'consent' ) ? 'aria-invalid="true" aria-describedby="ch-contact-err-consent"' : ''; ?> />
			<span><?php echo esc_html__( 'I’m happy for CuredHosting to contact me about this message. That’s all this permission covers.', 'curedhosting' ); ?> <span class="ch-req" aria-hidden="true">*</span></span>
		</label>
		<?php if ( curedhosting_field_error( $ch_state, 'consent' ) ) : ?>
			<p class="ch-field-error" id="ch-contact-err-consent"><?php echo esc_html( curedhosting_field_error( $ch_state, 'consent' ) ); ?></p>
		<?php endif; ?>
	</div>

	<p class="ch-form-notice"><?php echo esc_html__( 'This form starts a conversation — it does not create an account, charge anything, or commit you to a purchase. Your details stay on this website; nothing is sent to third-party services.', 'curedhosting' ); ?></p>

	<p>
		<button type="submit" class="ch-btn ch-btn--primary"><?php echo esc_html__( 'Send message', 'curedhosting' ); ?></button>
	</p>
</form>
