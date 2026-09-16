<?php
/**
 * Title: Hero — Clinic
 * Slug: cnc-theme/hero-clinic
 * Categories: cnc-sections
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:custom|spacing|section-gap","bottom":"var:custom|spacing|section-gap","left":"1.5rem","right":"1.5rem"}}},"backgroundColor":"accent-2","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-accent-2-background-color has-background" style="position:relative;overflow:hidden;padding-top:var(--wp--custom--spacing--section-gap);padding-right:1.5rem;padding-bottom:var(--wp--custom--spacing--section-gap);padding-left:1.5rem">

	<!-- wp:html -->
	<div class="cnc-hero-organics" aria-hidden="true">
		<svg class="organic-shape" data-depth="0.6" style="top:-6%;right:6%;width:150px;height:150px;"><use href="#organic-citrus"></use></svg>
		<svg class="organic-shape ink-gold" data-depth="1.1" style="top:12%;right:26%;width:60px;height:100px;"><use href="#organic-leaf"></use></svg>
		<svg class="organic-shape" data-depth="0.8" style="bottom:-8%;left:2%;width:90px;height:110px;"><use href="#organic-avocado"></use></svg>
		<svg class="organic-shape ink-citrus" data-depth="1.3" style="bottom:8%;left:20%;width:40px;height:110px;"><use href="#organic-grain"></use></svg>
	</div>
	<!-- /wp:html -->

	<!-- wp:columns {"verticalAlignment":"center"} -->
	<div class="wp-block-columns are-vertically-aligned-center">

		<!-- wp:column {"verticalAlignment":"center"} -->
		<div class="wp-block-column is-vertically-aligned-center">
			<!-- wp:paragraph {"className":"cnc-eyebrow","fontSize":"small","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.14em","fontWeight":"700"}},"textColor":"accent"} -->
			<p class="cnc-eyebrow has-accent-color has-text-color has-small-font-size" style="font-weight:700;letter-spacing:0.14em;text-transform:uppercase">Citadel Nutrition Consult</p>
			<!-- /wp:paragraph -->

			<!-- wp:heading {"level":1,"fontSize":"xx-large"} -->
			<h1 class="has-xx-large-font-size">Clinical nutrition care you can trust</h1>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"fontSize":"large"} -->
			<p class="has-large-font-size">Citadel Nutrition Consult provides consultations, hospital and gym partnerships, and medical nutrition therapy — grounded in evidence, delivered with care.</p>
			<!-- /wp:paragraph -->

			<!-- wp:buttons -->
			<div class="wp-block-buttons">
				<!-- wp:button {"className":"cnc-btn-magnetic"} -->
				<div class="wp-block-button cnc-btn-magnetic"><a class="wp-block-button__link wp-element-button" href="/book/">Book a Consultation</a></div>
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
			<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/patterns/hero-clinic.jpg' ) ); ?>" alt="The Citadel Nutrition Consult clinic in Jos, Plateau State" style="border-radius:16px"/></figure>
			<!-- /wp:image -->
		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

</div>
<!-- /wp:group -->
