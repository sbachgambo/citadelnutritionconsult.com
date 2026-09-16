<?php
/**
 * Title: Shop teaser
 * Slug: cnc-theme/shop-teaser
 * Categories: cnc-sections
 */
?>
<!-- wp:group {"tagName":"section","align":"wide","backgroundColor":"accent-2","style":{"spacing":{"padding":{"top":"var:custom|spacing|section-gap","bottom":"var:custom|spacing|section-gap","left":"2rem","right":"2rem"}},"border":{"radius":"16px"}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignwide has-accent-2-background-color has-background" style="position:relative;overflow:hidden;border-radius:26px;padding-top:var(--wp--custom--spacing--section-gap);padding-right:2rem;padding-bottom:var(--wp--custom--spacing--section-gap);padding-left:2rem">

	<!-- wp:html -->
	<div class="cnc-hero-organics" aria-hidden="true">
		<svg class="organic-shape ink-citrus" data-depth="0.8" style="top:-10%;right:10%;width:100px;height:100px;"><use href="#organic-citrus"></use></svg>
		<svg class="organic-shape ink-gold" data-depth="1.2" style="bottom:-12%;left:6%;width:60px;height:100px;"><use href="#organic-leaf"></use></svg>
	</div>
	<!-- /wp:html -->

	<!-- wp:columns {"verticalAlignment":"center"} -->
	<div class="wp-block-columns are-vertically-aligned-center">
		<!-- wp:column {"verticalAlignment":"center"} -->
		<div class="wp-block-column is-vertically-aligned-center">
			<!-- wp:heading -->
			<h2>CNC Smart Foods</h2>
			<!-- /wp:heading -->
			<!-- wp:paragraph -->
			<p>NAFDAC-listed therapeutic and treatment food packs, shipped to your door.</p>
			<!-- /wp:paragraph -->
			<!-- wp:buttons -->
			<div class="wp-block-buttons">
				<!-- wp:button -->
				<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/shop/">Shop Smart Foods</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column {"verticalAlignment":"center"} -->
		<div class="wp-block-column is-vertically-aligned-center">
			<!-- wp:image {"sizeSlug":"large","style":{"border":{"radius":"12px"}}} -->
			<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/patterns/shop-teaser.jpg' ) ); ?>" alt="CNC Smartfoods on the shelf at the Nutrition Hub" style="border-radius:16px"/></figure>
			<!-- /wp:image -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

</section>
<!-- /wp:group -->
