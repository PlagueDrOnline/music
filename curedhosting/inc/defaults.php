<?php
/**
 * CuredHosting default content.
 *
 * Every string here is a *default*. All of it is editable from
 * wp-admin → CuredHosting without touching code (see docs/content-guide.md).
 * Nothing here contains secrets or private contact data.
 *
 * @package CuredHosting
 */

defined( 'ABSPATH' ) || exit;

/**
 * All default settings for the theme.
 *
 * @return array<string, mixed>
 */
function curedhosting_defaults() {
	return array(

		/* ----------------------------- Contact ----------------------------- */

		'contact_email'      => 'hello@curedhosting.com',
		'support_email'      => 'support@curedhosting.com',
		'notify_email'       => '', // Empty = the WordPress admin email.
		'phone'              => '',
		'hours'              => 'Monday–Friday, 9:00–17:00. One person reads every message.',
		'location_note'      => 'A deliberately small operator. Twenty accounts, no more.',

		/* ------------------------------ Footer ----------------------------- */

		'footer_tagline'     => 'The cure for the common hosting headache.',
		'footer_infra_note'  => 'Sites run on InMotion reseller hosting with cPanel. We say so plainly — and we stand behind it personally.',
		'footer_legal_lines' => array(
			'All plans are subject to our fair-use, security, resource, and neighbor-protection rules.',
			'No “unlimited” claims. Just clear limits, honestly run.',
		),

		/* -------------------------- Grand opening -------------------------- */

		'promo_enabled'      => true,
		'promo_kicker'       => 'Grand opening — offered honestly',
		'promo_heading'      => 'A fair welcome offer, written in plain language',
		'promo_intro'        => 'We would rather earn twenty long-term customers than rush a thousand through a checkout. So the grand-opening offer is simple, and the fine print is printed large.',
		'promo_points'       => array(
			'Pay for a year, billed like ten — annual hosting is invoiced at the equivalent of ten monthly payments.',
			'First-year domain included — one free domain registration for the first year, subject to availability and eligible extensions.',
			'While the grand opening runs, we match your annual term: buy a year of hosting and we add a second year, applied to your current domain or an eligible new one.',
		),
		'promo_terms'        => 'Eligibility, renewal pricing, domain availability, plan eligibility, and final terms are confirmed with you in writing before any purchase. The included domain registration covers the first year only — renewals after that are billed at standard rates. Nothing here is a countdown, and nothing expires tonight.',
		'promo_link_label'   => 'Read the full grand-opening terms',
		'promo_link_url'     => '/grand-opening/',

		/* ------------------------------ Plans ------------------------------ */

		'plans_fair_use_note' => 'Every plan is subject to our fair-use, security, resource, and neighbor-protection rules. We never say “unlimited” — you get clear limits, honestly run.',
		'plans_price_note'    => 'Pricing is confirmed on your intro call, in writing, before anything is billed.',
		'plans_cta_label'     => 'Start with a conversation',
		'plans_cta_url'       => '/onboarding/',

		'plans'               => array(
			'wp_starter'     => array(
				'name'        => 'WordPress Starter',
				'tagline'     => 'One WordPress site, set up properly from day one.',
				'price_note'  => '',
				'is_wordpress' => true,
				'storage'     => '5 GB storage',
				'bandwidth'   => '50 GB monthly bandwidth',
				'databases'   => '3 MySQL databases',
				'subdomains'  => '10 subdomains',
				'emails'      => '10 email accounts',
				'ftp'         => '2 FTP accounts',
				'extras'      => array(
					'WordPress preinstalled',
					'1 standard WordPress site',
				),
				'best_for'    => 'A first site, a brochure site, or a single blog that deserves better than a queue.',
				'cta_label'   => '',
				'cta_url'     => '',
			),
			'wp_business'    => array(
				'name'        => 'WordPress Business',
				'tagline'     => 'For a working website your business actually depends on.',
				'price_note'  => '',
				'is_wordpress' => true,
				'storage'     => '10 GB storage',
				'bandwidth'   => '100 GB monthly bandwidth',
				'databases'   => '8 MySQL databases',
				'subdomains'  => '25 subdomains',
				'emails'      => '25 email accounts',
				'ftp'         => '3 FTP accounts',
				'extras'      => array(
					'WordPress preinstalled',
					'Staging-friendly setup',
				),
				'best_for'    => 'A business site that can’t afford guesswork — with room to test changes before they ship.',
				'cta_label'   => '',
				'cta_url'     => '',
			),
			'linux_starter'  => array(
				'name'        => 'Linux Starter',
				'tagline'     => 'A tidy cPanel account for one small non-WordPress site.',
				'price_note'  => '',
				'is_wordpress' => false,
				'storage'     => '5 GB storage',
				'bandwidth'   => '50 GB monthly bandwidth',
				'databases'   => '3 MySQL databases',
				'subdomains'  => '10 subdomains',
				'emails'      => '10 email accounts',
				'ftp'         => '2 FTP accounts',
				'extras'      => array(
					'Suitable for one small non-WordPress site or application',
				),
				'best_for'    => 'One small static site or lightweight application, looked after by a human.',
				'cta_label'   => '',
				'cta_url'     => '',
			),
			'linux_business' => array(
				'name'        => 'Linux Business',
				'tagline'     => 'Room for several projects, run within fair use.',
				'price_note'  => '',
				'is_wordpress' => false,
				'storage'     => '15 GB storage',
				'bandwidth'   => '150 GB monthly bandwidth',
				'databases'   => '15 MySQL databases',
				'subdomains'  => '30 subdomains',
				'emails'      => '40 email accounts',
				'ftp'         => '5 FTP accounts',
				'extras'      => array(
					'Suitable for multiple domains or applications within fair use',
				),
				'best_for'    => 'Multiple domains or applications for one owner or a small team, within fair use.',
				'cta_label'   => '',
				'cta_url'     => '',
			),
		),

		/* ------------------------------- FAQ ------------------------------- */

		'faq'                 => array(
			array(
				'q' => 'Why do you cap CuredHosting at twenty accounts?',
				'a' => 'Because “personal support” stops being true once one person can’t remember your setup. Twenty accounts is the size at which we can genuinely know every site, every domain, and every renewal — and answer quickly when something needs attention.',
			),
			array(
				'q' => 'Can I just check out and buy a plan online?',
				'a' => 'Not yet — and that’s deliberate. Every account starts with a short conversation so we can confirm the plan fits, the terms are clear in writing, and (for the grand-opening offer) your eligibility is confirmed before you pay anything.',
			),
			array(
				'q' => 'Whose servers does my site run on?',
				'a' => 'InMotion reseller hosting, managed with cPanel. We don’t dress that up as dedicated hardware. What we add is careful configuration, monitoring, honest limits, and a person who stands behind the account.',
			),
			array(
				'q' => 'What exactly does the grand-opening offer include?',
				'a' => 'Annual hosting billed at the equivalent of ten monthly payments, one free domain registration for the first year (subject to availability and eligible extensions), and — while the grand opening runs — an extra matching year of hosting applied to your current domain or an eligible new one. Renewal pricing and eligibility are confirmed with you in writing first. Renewals after year one are billed at standard rates.',
			),
			array(
				'q' => 'Will you migrate my existing WordPress site?',
				'a' => 'Yes — standard WordPress migrations are included: files, uploads, database, a configuration review, basic compatibility and permalink checks, SSL and launch verification, and DNS guidance. Redesigns, compromised-site cleanup, mailbox migration, and unusual reconstruction are scoped separately, with your go-ahead first.',
			),
			array(
				'q' => 'What happens if I outgrow my plan’s limits?',
				'a' => 'We talk. Limits exist to protect every account on the server, so we’ll tell you early if you’re approaching one, and we’ll help you move to a bigger plan — or advise honestly if something else fits better.',
			),
			array(
				'q' => 'Is email hosting included?',
				'a' => 'Each plan includes a set number of email mailboxes on your domain (see the plan limits). Moving existing mailbox *data* from another provider is not part of standard migration and needs separate review — we’ll be upfront about that before you commit.',
			),
			array(
				'q' => 'What does “fair use” mean in practice?',
				'a' => 'It means your site gets the resources it needs, and no single account can crowd out the neighbors. Mass mailing, file-hosting for other sites, cryptocurrency mining, and abusive automation are not what these accounts are for. The full policy is written in plain language on the fair-use page.',
			),
		),
	);
}

/**
 * Ordered list of structured limit fields shown on plan cards and in the
 * comparison table. Keeping this in one place means the card and the
 * table can never drift apart.
 *
 * @return array<int, array{key: string, label: string}>
 */
function curedhosting_plan_limit_fields() {
	return array(
		array(
			'key'   => 'storage',
			'label' => __( 'Storage', 'curedhosting' ),
		),
		array(
			'key'   => 'bandwidth',
			'label' => __( 'Monthly bandwidth', 'curedhosting' ),
		),
		array(
			'key'   => 'databases',
			'label' => __( 'MySQL databases', 'curedhosting' ),
		),
		array(
			'key'   => 'subdomains',
			'label' => __( 'Subdomains', 'curedhosting' ),
		),
		array(
			'key'   => 'emails',
			'label' => __( 'Email accounts', 'curedhosting' ),
		),
		array(
			'key'   => 'ftp',
			'label' => __( 'FTP accounts', 'curedhosting' ),
		),
	);
}
