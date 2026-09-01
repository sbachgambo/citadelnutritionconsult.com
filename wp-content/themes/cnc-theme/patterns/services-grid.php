<?php
/**
 * Title: Services grid
 * Slug: cnc-theme/services-grid
 * Categories: cnc-sections
 */
?>
<!-- wp:group {"tagName":"section","align":"wide","anchor":"services","style":{"spacing":{"padding":{"top":"var:custom|spacing|section-gap","bottom":"var:custom|spacing|section-gap"}}},"layout":{"type":"constrained"}} -->
<section id="services" class="wp-block-group alignwide" style="padding-top:var(--wp--custom--spacing--section-gap);padding-bottom:var(--wp--custom--spacing--section-gap)">

	<!-- wp:heading {"textAlign":"center"} -->
	<h2 class="has-text-align-center">Our Services</h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"align":"center"} -->
	<p class="has-text-align-center">Add, edit, or reorder services from the <strong>Services</strong> section in the WordPress admin — this grid updates automatically.</p>
	<!-- /wp:paragraph -->

	<!-- wp:query {"query":{"perPage":6,"pages":0,"offset":0,"postType":"service","order":"asc","orderBy":"menu_order"},"displayLayout":{"type":"flex","columns":3}} -->
	<div class="wp-block-query">
		<!-- wp:post-template -->
			<!-- wp:group {"style":{"border":{"width":"1px","radius":"8px"},"spacing":{"padding":{"top":"1.5rem","bottom":"1.5rem","left":"1.5rem","right":"1.5rem"}}},"borderColor":"border","layout":{"type":"default"}} -->
			<div class="wp-block-group has-border-color has-border cnc-service-card" style="border-width:1px;border-radius:8px;padding-top:1.5rem;padding-right:1.5rem;padding-bottom:1.5rem;padding-left:1.5rem">
				<!-- wp:post-featured-image {"height":"160px","className":"cnc-service-photo"} /-->
				<!-- wp:post-title {"level":3,"isLink":false} /-->
				<!-- wp:post-excerpt /-->
			</div>
			<!-- /wp:group -->
		<!-- /wp:post-template -->
	</div>
	<!-- /wp:query -->

	<!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"top":"2.5rem"}}}} -->
	<p class="has-text-align-center" style="margin-top:2.5rem"><a href="/services/">View all services &amp; pricing</a></p>
	<!-- /wp:paragraph -->

</section>
<!-- /wp:group -->
