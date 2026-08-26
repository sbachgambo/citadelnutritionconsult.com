<?php
/**
 * Title: Learn teaser
 * Slug: cnc-theme/learn-teaser
 * Categories: cnc-sections
 */
?>
<!-- wp:group {"tagName":"section","align":"wide","style":{"spacing":{"padding":{"top":"var:custom|spacing|section-gap","bottom":"var:custom|spacing|section-gap","left":"2rem","right":"2rem"}},"border":{"radius":"16px","width":"1px"}},"borderColor":"border","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignwide has-border-color has-border" style="border-width:1px;border-radius:16px;padding-top:var(--wp--custom--spacing--section-gap);padding-right:2rem;padding-bottom:var(--wp--custom--spacing--section-gap);padding-left:2rem">

	<!-- wp:columns {"verticalAlignment":"center"} -->
	<div class="wp-block-columns are-vertically-aligned-center">
		<!-- wp:column {"verticalAlignment":"center"} -->
		<div class="wp-block-column is-vertically-aligned-center">
			<!-- wp:image {"sizeSlug":"large","style":{"border":{"radius":"12px"}}} -->
			<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/patterns/learn-teaser.jpg' ) ); ?>" alt="Dietitian Michelle holding the Healing Recipes e-book" style="border-radius:12px"/></figure>
			<!-- /wp:image -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column {"verticalAlignment":"center"} -->
		<div class="wp-block-column is-vertically-aligned-center">
			<!-- wp:heading -->
			<h2>Learn: Courses &amp; E-book</h2>
			<!-- /wp:heading -->
			<!-- wp:paragraph -->
			<p>Go deeper with Michelle's guided courses and e-book — purchased and delivered through Selar.</p>
			<!-- /wp:paragraph -->
			<!-- wp:buttons -->
			<div class="wp-block-buttons">
				<!-- wp:button {"className":"is-style-outline"} -->
				<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="/learn/">Browse Learn</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

</section>
<!-- /wp:group -->
