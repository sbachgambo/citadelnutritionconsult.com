<?php
/**
 * Title: FAQ
 * Slug: cnc-theme/faq
 * Categories: cnc-sections
 */
?>
<!-- wp:group {"tagName":"section","align":"wide","style":{"spacing":{"padding":{"top":"var:custom|spacing|section-gap","bottom":"var:custom|spacing|section-gap"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignwide" style="padding-top:var(--wp--custom--spacing--section-gap);padding-bottom:var(--wp--custom--spacing--section-gap)">

	<!-- wp:heading {"textAlign":"center"} -->
	<h2 class="has-text-align-center">Frequently asked questions</h2>
	<!-- /wp:heading -->

	<!-- wp:query {"query":{"perPage":10,"pages":0,"offset":0,"postType":"faq_item","order":"asc","orderBy":"menu_order"},"layout":{"type":"constrained"}} -->
	<div class="wp-block-query">
		<!-- wp:post-template -->
			<!-- wp:group {"style":{"spacing":{"padding":{"top":"1rem","bottom":"1rem"},"blockGap":"0.25rem"},"border":{"bottom":{"width":"1px"},"top":{"width":"0px"},"left":{"width":"0px"},"right":{"width":"0px"}}},"borderColor":"border","layout":{"type":"default"}} -->
			<div class="wp-block-group has-border-color has-border" style="border-top-width:0px;border-right-width:0px;border-bottom-width:1px;border-left-width:0px;padding-top:1rem;padding-bottom:1rem">
				<!-- wp:post-title {"level":3,"isLink":false,"fontSize":"medium"} /-->
				<!-- wp:post-content /-->
			</div>
			<!-- /wp:group -->
		<!-- /wp:post-template -->
	</div>
	<!-- /wp:query -->

</section>
<!-- /wp:group -->
