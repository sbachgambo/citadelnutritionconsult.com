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
	<p class="has-text-align-center">Straight from the current CNC pricing flyer. Physical consultations available at the Jos clinic; phone/virtual consultations also available for every plan.</p>
	<!-- /wp:paragraph -->

	<!-- wp:table {"hasFixedLayout":false,"style":{"spacing":{"margin":{"top":"2rem"}}}} -->
	<figure class="wp-block-table" style="margin-top:2rem">
	<table>
		<thead>
			<tr><th>Plan</th><th>Price</th><th>Key features</th></tr>
		</thead>
		<tbody>
			<tr><td>Consultation</td><td>₦15,000</td><td>Nutritional assessment, 30–45 min session, 7-day general diet plan, consultation action points, access to the Support Group</td></tr>
			<tr><td>Customized Plan</td><td>₦30,000</td><td>Everything in Consultation, plus a fully customized 7-day diet plan with variety column, detailed guidelines, and shopping list — ready 5–7 working days after your session</td></tr>
			<tr><td>Gold Plan</td><td>₦60,000</td><td>Customized Plan, plus a complimentary diet-treatment video and a CNC Smartfoods starter box</td></tr>
			<tr><td>Premium Plan</td><td>₦180,000</td><td>Gold Plan, plus 4 weeks in the Private Review Group and a 4-recipe culinary tutorial (or an extra ½ box of Smartfoods where a CNC Diet Chef isn't available)</td></tr>
			<tr><td>Citadel Plan</td><td>₦380,000</td><td>Premium Plan, plus 12 weeks in the Private Review Group, an 8-recipe culinary tutorial, and 2 boxes of Smartfoods</td></tr>
			<tr><td>Diet Plan Only</td><td>₦15,000</td><td>Just the diet plan, no consultation session</td></tr>
			<tr><td>Follow Up</td><td>₦7,500</td><td>A single follow-up session for existing clients</td></tr>
		</tbody>
	</table>
	</figure>
	<!-- /wp:table -->

</section>
<!-- /wp:group -->

<!-- wp:pattern {"slug":"cnc-theme/booking-cta"} /-->
