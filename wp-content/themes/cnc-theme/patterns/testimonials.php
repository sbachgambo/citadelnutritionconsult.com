<?php
/**
 * Title: Testimonials
 * Slug: cnc-theme/testimonials
 * Categories: cnc-sections
 */
?>
<!-- wp:group {"tagName":"section","align":"wide","backgroundColor":"surface","style":{"spacing":{"padding":{"top":"var:custom|spacing|section-gap","bottom":"var:custom|spacing|section-gap"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignwide has-surface-background-color has-background" style="padding-top:var(--wp--custom--spacing--section-gap);padding-bottom:var(--wp--custom--spacing--section-gap)">

	<!-- wp:heading {"textAlign":"center"} -->
	<h2 class="has-text-align-center">What clients say</h2>
	<!-- /wp:heading -->

	<!-- wp:query {"query":{"perPage":3,"pages":0,"offset":0,"postType":"testimonial","order":"desc","orderBy":"date"},"displayLayout":{"type":"flex","columns":3}} -->
	<div class="wp-block-query">
		<!-- wp:post-template -->
			<!-- wp:group {"style":{"spacing":{"padding":{"top":"1.5rem","bottom":"1.5rem","left":"1.5rem","right":"1.5rem"}}},"backgroundColor":"base","layout":{"type":"default"}} -->
			<div class="wp-block-group has-base-background-color has-background" style="padding-top:1.5rem;padding-right:1.5rem;padding-bottom:1.5rem;padding-left:1.5rem">
				<!-- wp:post-excerpt {"excerptLength":40} /-->
				<!-- wp:post-title {"level":4,"isLink":false,"fontSize":"small"} /-->
			</div>
			<!-- /wp:group -->
		<!-- /wp:post-template -->
	</div>
	<!-- /wp:query -->

</section>
<!-- /wp:group -->
