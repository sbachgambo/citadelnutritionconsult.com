<?php
/**
 * Title: Blog — full listing
 * Slug: cnc-theme/blog-full
 * Categories: cnc-sections
 */
?>
<!-- wp:group {"tagName":"section","align":"wide","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignwide">

	<!-- wp:query {"query":{"perPage":9,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","inherit":false},"displayLayout":{"type":"flex","columns":3}} -->
	<div class="wp-block-query">
		<!-- wp:post-template -->
			<!-- wp:group {"style":{"border":{"width":"1px","radius":"8px"},"spacing":{"padding":{"top":"1.5rem","bottom":"1.5rem","left":"1.5rem","right":"1.5rem"}}},"borderColor":"border","layout":{"type":"default"}} -->
			<div class="wp-block-group has-border-color has-border" style="border-width:1px;border-radius:8px;padding-top:1.5rem;padding-right:1.5rem;padding-bottom:1.5rem;padding-left:1.5rem">
				<!-- wp:post-featured-image {"height":"160px","style":{"border":{"radius":"6px"}}} /-->
				<!-- wp:post-title {"level":3,"isLink":true} /-->
				<!-- wp:post-excerpt /-->
			</div>
			<!-- /wp:group -->
		<!-- /wp:post-template -->

		<!-- wp:query-pagination -->
		<!-- wp:query-pagination-previous /-->
		<!-- wp:query-pagination-numbers /-->
		<!-- wp:query-pagination-next /-->
		<!-- /wp:query-pagination -->

		<!-- wp:query-no-results -->
		<!-- wp:paragraph -->
		<p>No posts yet — check back soon.</p>
		<!-- /wp:paragraph -->
		<!-- /wp:query-no-results -->
	</div>
	<!-- /wp:query -->

</section>
<!-- /wp:group -->
