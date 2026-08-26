<?php
/**
 * Title: Hero — Clinic
 * Slug: cnc-theme/hero-clinic
 * Categories: cnc-sections
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:custom|spacing|section-gap","bottom":"var:custom|spacing|section-gap","left":"1.5rem","right":"1.5rem"}}},"backgroundColor":"accent-2","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-accent-2-background-color has-background" style="padding-top:var(--wp--custom--spacing--section-gap);padding-right:1.5rem;padding-bottom:var(--wp--custom--spacing--section-gap);padding-left:1.5rem">

	<!-- wp:columns {"verticalAlignment":"center"} -->
	<div class="wp-block-columns are-vertically-aligned-center">

		<!-- wp:column {"verticalAlignment":"center"} -->
		<div class="wp-block-column is-vertically-aligned-center">
			<!-- wp:heading {"level":1,"fontSize":"xx-large"} -->
			<h1 class="has-xx-large-font-size">Clinical nutrition care you can trust</h1>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"fontSize":"large"} -->
			<p class="has-large-font-size">Citadel Nutrition Consult provides consultations, hospital and gym partnerships, and medical nutrition therapy — grounded in evidence, delivered with care.</p>
			<!-- /wp:paragraph -->

			<!-- wp:buttons -->
			<div class="wp-block-buttons">
				<!-- wp:button -->
				<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/book/">Book a Consultation</a></div>
				<!-- /wp:button -->
				<!-- wp:button {"className":"is-style-outline"} -->
				<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="#services">Our Services</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"verticalAlignment":"center"} -->
		<div class="wp-block-column is-vertically-aligned-center">
			<!-- wp:image {"sizeSlug":"large","style":{"border":{"radius":"12px"}}} -->
			<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/patterns/hero-clinic.jpg' ) ); ?>" alt="The Citadel Nutrition Consult clinic in Jos, Plateau State" style="border-radius:12px"/></figure>
			<!-- /wp:image -->
		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

</div>
<!-- /wp:group -->
