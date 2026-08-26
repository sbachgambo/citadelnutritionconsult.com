<?php
/**
 * Title: Services — full list with pricing
 * Slug: cnc-theme/services-full
 * Categories: cnc-sections
 */
?>
<!-- wp:group {"tagName":"section","align":"wide","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignwide">

	<!-- wp:query {"query":{"perPage":12,"pages":0,"offset":0,"postType":"service","order":"asc","orderBy":"menu_order"},"displayLayout":{"type":"flex","columns":3}} -->
	<div class="wp-block-query">
		<!-- wp:post-template -->
			<!-- wp:group {"style":{"border":{"width":"1px","radius":"8px"},"spacing":{"padding":{"top":"1.5rem","bottom":"1.5rem","left":"1.5rem","right":"1.5rem"}}},"borderColor":"border","layout":{"type":"default"}} -->
			<div class="wp-block-group has-border-color has-border" style="border-width:1px;border-radius:8px;padding-top:1.5rem;padding-right:1.5rem;padding-bottom:1.5rem;padding-left:1.5rem">
				<!-- wp:post-featured-image {"height":"56px","width":"56px","style":{"border":{"radius":"0px"}}} /-->
				<!-- wp:post-title {"level":3,"isLink":false} /-->
				<!-- wp:post-excerpt /-->
			</div>
			<!-- /wp:group -->
		<!-- /wp:post-template -->
	</div>
	<!-- /wp:query -->

</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"wide","backgroundColor":"surface","style":{"spacing":{"padding":{"top":"var:custom|spacing|section-gap","bottom":"var:custom|spacing|section-gap"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignwide has-surface-background-color has-background" style="padding-top:var(--wp--custom--spacing--section-gap);padding-bottom:var(--wp--custom--spacing--section-gap)">

	<!-- wp:heading {"textAlign":"center"} -->
	<h2 class="has-text-align-center">Consultation Packages</h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"align":"center"} -->
	<p class="has-text-align-center">Starting prices — confirm against the current live pricing before publishing.</p>
	<!-- /wp:paragraph -->

	<!-- wp:table {"hasFixedLayout":false,"style":{"spacing":{"margin":{"top":"2rem"}}}} -->
	<figure class="wp-block-table" style="margin-top:2rem">
	<table>
		<thead>
			<tr><th>Plan</th><th>Price</th><th>Key features</th></tr>
		</thead>
		<tbody>
			<tr><td>Nutritional Assessment</td><td>₦3,000</td><td>Blood pressure &amp; blood sugar testing, BMI checkup</td></tr>
			<tr><td>Basic Plan</td><td>₦10,000</td><td>30–45 min consultation, 7-day diet plan, support group access</td></tr>
			<tr><td>Customized Plan</td><td>₦30,000</td><td>28-day personalized diet plan, starter items included</td></tr>
			<tr><td>Gold Plan</td><td>₦60,000</td><td>Customized plan + CNC Smartfood box + video tutorial</td></tr>
			<tr><td>Premium Plan</td><td>₦180,000</td><td>Gold plan + 4-week private review + 4-recipe culinary session</td></tr>
			<tr><td>Citadel Plan</td><td>₦360,000</td><td>Premium plan + 12-week private review + 8-recipe culinary session</td></tr>
		</tbody>
	</table>
	</figure>
	<!-- /wp:table -->

</section>
<!-- /wp:group -->

<!-- wp:pattern {"slug":"cnc-theme/booking-cta"} /-->
