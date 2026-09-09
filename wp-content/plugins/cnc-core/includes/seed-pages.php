<?php
/**
 * Creates the actual WordPress Pages the theme's nav/footer link to
 * (/services/, /about-michelle/, /shop/, /learn/, /communities/, /book/,
 * /blog/), each with its matching custom template assigned — plus a real
 * Privacy Policy and a Terms & Conditions page. None of this existed
 * before: on a genuinely fresh install, cnc_core_seed_content() seeds the
 * CPT content the templates query, but nothing previously created the
 * Pages themselves, so every nav link would 404 on a first-ever deploy.
 *
 * Runs once (`cnc_core_pages_seeded` option guards it); staff can freely
 * edit/delete any of it afterward like any other page.
 */

defined( 'ABSPATH' ) || exit;

function cnc_core_seed_pages() {
	if ( get_option( 'cnc_core_pages_seeded' ) ) {
		return;
	}

	$pages = array(
		array(
			'slug'     => 'about-michelle',
			'title'    => 'About Michelle',
			'template' => 'page-about-michelle',
			'content'  => '',
		),
		array(
			'slug'     => 'services',
			'title'    => 'Services',
			'template' => 'page-services',
			'content'  => "<!-- wp:paragraph -->\n<p>Clinical expertise combined with practical, culturally-grounded guidance — not quick fixes, sustainable transformation.</p>\n<!-- /wp:paragraph -->",
		),
		array(
			'slug'     => 'shop',
			'title'    => 'CNC Smartfoods',
			'template' => 'page-shop',
			'content'  => '',
		),
		array(
			'slug'     => 'learn',
			'title'    => 'Learn',
			'template' => 'page-learn',
			'content'  => "<!-- wp:paragraph -->\n<p>Expertly designed nutrition guides, video courses, and e-books tailored to specific health goals.</p>\n<!-- /wp:paragraph -->",
		),
		array(
			'slug'     => 'communities',
			'title'    => 'CNC Communities',
			'template' => 'page-communities',
			'content'  => '',
		),
		array(
			'slug'     => 'book',
			'title'    => 'Book a Consultation',
			'template' => 'page-book',
			'content'  => "<!-- wp:paragraph -->\n<p>Booking and managing consultations happens entirely inside the Diet Padi app, or reach us directly on WhatsApp.</p>\n<!-- /wp:paragraph -->",
		),
		array(
			'slug'     => 'blog',
			'title'    => 'Blog',
			'template' => 'page-blog',
			'content'  => '',
		),
		array(
			'slug'     => 'terms-and-conditions',
			'title'    => 'Terms & Conditions',
			'template' => '',
			'content'  => cnc_core_terms_content(),
		),
	);

	foreach ( $pages as $page ) {
		$existing = get_page_by_path( $page['slug'] );
		if ( $existing ) {
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_title'   => $page['title'],
				'post_name'    => $page['slug'],
				'post_content' => $page['content'],
				'post_status'  => 'publish',
			)
		);

		if ( $post_id && ! is_wp_error( $post_id ) && ! empty( $page['template'] ) ) {
			update_post_meta( $post_id, '_wp_page_template', $page['template'] );
		}
	}

	// WordPress core creates a draft "Privacy Policy" page (usually post ID 3)
	// on install but leaves it empty — fill it in with real content, publish
	// it, and register it as the site's official privacy policy page so the
	// "This site uses cookies..." admin notice and REST API privacy tools work.
	$privacy_id = (int) get_option( 'wp_page_for_privacy_policy' );
	if ( ! $privacy_id ) {
		$existing_privacy = get_page_by_path( 'privacy-policy' );
		$privacy_id       = $existing_privacy ? $existing_privacy->ID : 0;
	}
	if ( $privacy_id ) {
		wp_update_post(
			array(
				'ID'           => $privacy_id,
				'post_title'   => 'Privacy Policy',
				'post_content' => cnc_core_privacy_policy_content(),
				'post_status'  => 'publish',
			)
		);
		update_option( 'wp_page_for_privacy_policy', $privacy_id );
	}

	update_option( 'cnc_core_pages_seeded', true );
}

function cnc_core_privacy_policy_content() {
	return <<<'HTML'
<!-- wp:paragraph -->
<p><em>Draft — please have this reviewed against your actual data handling and by counsel familiar with the Nigeria Data Protection Act before relying on it.</em></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Citadel Nutrition Consult ("CNC", "we", "us") provides clinical nutrition consultations, digital nutrition products, and CNC Smartfoods products. This policy explains what personal data we collect through this website, how we use it, and your rights over it, in line with the Nigeria Data Protection Act 2023.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>What we collect</h2>
<!-- /wp:heading -->
<!-- wp:list -->
<ul>
<!-- wp:list-item --><li><strong>Contact details</strong> — name, email, phone number, and delivery address, when you place an order or reach out via WhatsApp.</li><!-- /wp:list-item -->
<!-- wp:list-item --><li><strong>Health information you choose to share</strong> during a consultation, used only to provide clinical nutrition guidance to you.</li><!-- /wp:list-item -->
<!-- wp:list-item --><li><strong>Order and payment information</strong> — payments are processed by Paystack; we do not see or store your card details.</li><!-- /wp:list-item -->
<!-- wp:list-item --><li><strong>Basic technical data</strong> (browser, device, pages visited) collected automatically to keep the site secure and working correctly.</li><!-- /wp:list-item -->
</ul>
<!-- /wp:list -->

<!-- wp:heading -->
<h2>How we use it</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>To fulfil orders and consultations, respond to enquiries, provide the nutrition guidance you've requested, and — only with your consent — send updates about our services. We do not sell your personal data.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Who we share it with</h2>
<!-- /wp:heading -->
<!-- wp:list -->
<ul>
<!-- wp:list-item --><li><strong>Paystack</strong>, to process payments.</li><!-- /wp:list-item -->
<!-- wp:list-item --><li><strong>Selar</strong>, for checkout and delivery of digital products (e-books and courses).</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Delivery partners, solely to fulfil physical Smartfoods orders.</li><!-- /wp:list-item -->
</ul>
<!-- /wp:list -->

<!-- wp:heading -->
<h2>Your rights</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>You can ask us what personal data we hold about you, ask us to correct it, or ask us to delete it, subject to any records we're legally required to keep. Contact us using the details below to make a request.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Cookies</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>This site uses cookies required for shopping cart and checkout functionality, and — if enabled — analytics cookies to help us understand how the site is used. You can control cookies through your browser settings.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Contact</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Questions about this policy or your data: hello@cnutriconsult.com, or Crispan Hotel, Plot 9259, Off Jonah David Jang Exp. Way, Rayfield, Jos, Plateau State, Nigeria.</p>
<!-- /wp:paragraph -->
HTML;
}

function cnc_core_terms_content() {
	return <<<'HTML'
<!-- wp:paragraph -->
<p><em>Draft — please have this reviewed and adjusted (especially the refund/delivery terms) by counsel before relying on it.</em></p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>About these terms</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>These terms apply whenever you use this website, book a consultation with Citadel Nutrition Consult ("CNC", "we", "us"), buy a CNC Smartfoods product, or purchase a digital product (e-book or course) linked from this site. By using the site or placing an order, you agree to them.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Consultations</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Consultations are booked and managed through the Diet Padi app or directly by WhatsApp. The nutrition guidance provided is educational and clinical in nature but is not a substitute for medical diagnosis or treatment — always consult a physician for medical conditions, and seek emergency care through appropriate medical channels, not through this site.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Digital products</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>E-books and courses are sold and delivered through Selar. Because these are instantly accessible digital files, they are non-refundable once downloaded or accessed, except where required by law.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>CNC Smartfoods products</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Orders are processed and paid for through Paystack. Some products are only available for delivery within Jos, Plateau State — this is noted on each affected product. If an item arrives damaged or incorrect, contact us within 48 hours of delivery at hello@cnutriconsult.com and we'll arrange a replacement or refund.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Intellectual property</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>All content on this site — including diet plans, e-books, course materials, and branding — belongs to Citadel Nutrition Consult and may not be reproduced or resold without our written permission.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Liability</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>We provide guidance in good faith based on the information you share with us, but individual results vary and we can't guarantee specific health outcomes. To the extent permitted by law, CNC isn't liable for indirect or consequential loss arising from use of the site or our services.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Governing law</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>These terms are governed by the laws of the Federal Republic of Nigeria.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Changes &amp; contact</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>We may update these terms from time to time; the current version is always the one published here. Questions: hello@cnutriconsult.com.</p>
<!-- /wp:paragraph -->
HTML;
}
