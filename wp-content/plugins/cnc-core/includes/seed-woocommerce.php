<?php
/**
 * Seeds CNC Smartfoods products into WooCommerce with the real names,
 * prices, and package photography gathered from the live site. Only runs
 * once WooCommerce is active — CNC Core doesn't require it, since the
 * Services/Testimonials/FAQ/Digital Product content works without it.
 *
 * Unlike the CPT seeding in seed-content.php (which runs once on plugin
 * activation), this listens for WooCommerce to become active at any point
 * — activation order between CNC Core and WooCommerce isn't guaranteed —
 * and seeds exactly once via its own `cnc_core_woocommerce_seeded` flag.
 */

defined( 'ABSPATH' ) || exit;

function cnc_core_maybe_seed_woocommerce() {
	if ( get_option( 'cnc_core_woocommerce_seeded' ) ) {
		return;
	}
	if ( ! class_exists( 'WooCommerce' ) || ! function_exists( 'wc_get_product' ) ) {
		return;
	}

	$products = array(
		array(
			'name'        => 'Acha Flour (900g)',
			'description' => 'Gluten-free fonio flour — suitable for swallow and pancake. Rich in fibre and protein; helps control blood sugar. NAFDAC-registered.',
			'sku'         => 'CNC-ACHA-FLOUR-900',
			'price'       => 3960,
			'category'    => 'CNC Products',
			'image'       => 'acha-flour.png',
		),
		array(
			'name'        => 'Acha Grains (900g)',
			'description' => 'Whole-grain fonio — suitable for jollof, moi-moi, pudding, and swallow. NAFDAC-registered.',
			'sku'         => 'CNC-ACHA-GRAINS-900',
			'price'       => 3960,
			'category'    => 'CNC Products',
			'image'       => 'acha-grains.png',
		),
		array(
			'name'        => 'Bean Flour (900g)',
			'description' => 'Made with beans — suitable for akara, moi-moi, pancake, and low-fat akara. NAFDAC-registered.',
			'sku'         => 'CNC-BEAN-FLOUR-900',
			'price'       => 3600,
			'category'    => 'CNC Products',
			'image'       => 'bean-flour.png',
		),
		array(
			'name'        => 'CNC Moringa Detox (Tea Bags)',
			'description' => 'Herbal tea bags positioned for general wellness and detox support. No preservatives or artificial color.',
			'sku'         => 'CNC-MORINGA-TEA',
			'price'       => 4800,
			'category'    => 'CNC Beverages and Teas',
			'image'       => 'moringa.png',
		),
		array(
			'name'        => 'CNC Cinnamon (Tea Bags)',
			'description' => 'Metabolic-balance tea bags, positioned within CNC\'s blood-sugar-friendly beverage range.',
			'sku'         => 'CNC-CINNAMON-TEA',
			'price'       => 5400,
			'category'    => 'CNC Beverages and Teas',
			'image'       => 'cinnamon.png',
		),
		array(
			'name'        => 'CNC Zobo Drink (50cl)',
			'description' => 'Bottled natural hibiscus beverage. No artificial flavour, no preservative.',
			'sku'         => 'CNC-ZOBO-DRINK-50CL',
			'price'       => 4800,
			'category'    => 'CNC Beverages and Teas',
			'image'       => 'zobo-drink.png',
		),
		array(
			'name'        => 'CNC Tigernut Drink (50cl)',
			'description' => 'Sweetened with sugar, 100% natural, no artificial flavour or preservative.',
			'sku'         => 'CNC-TIGERNUT-50CL',
			'price'       => 1500,
			'category'    => 'CNC Beverages and Teas',
			'image'       => 'tigernut.png',
		),
		// Real package photography exists for these, but none were found for
		// individual sale on the live shop as of 2026-08-26 (confirmed by
		// paging through the full catalog) — seeded as drafts with no price
		// so they don't appear to customers until confirmed and published.
		array(
			'name'        => 'Finger Millet Flour (900g) — "Tamba"',
			'description' => 'Finger millet with ginger and cloves, suitable for pap. Price pending confirmation.',
			'sku'         => 'CNC-FINGER-MILLET-900',
			'price'       => null,
			'category'    => 'CNC Products',
			'image'       => 'finger-millet.png',
			'draft'       => true,
		),
		array(
			'name'        => 'Plantain Smartmix (900g)',
			'description' => 'Plantain-based blend for a quick, wholesome swallow. Price pending confirmation.',
			'sku'         => 'CNC-PLANTAIN-SMARTMIX-900',
			'price'       => null,
			'category'    => 'CNC Products',
			'image'       => 'plantain-smartmix.png',
			'draft'       => true,
		),
		array(
			'name'        => 'Tom Brown (900g)',
			'description' => 'Guinea corn, soybeans, groundnut, and sugar — a traditional Nigerian pap. Price pending confirmation.',
			'sku'         => 'CNC-TOM-BROWN-900',
			'price'       => null,
			'category'    => 'CNC Products',
			'image'       => 'tom-brown.png',
			'draft'       => true,
		),
		array(
			'name'        => 'CNC Zobo (Tea Bags)',
			'description' => 'Hibiscus tea bags — the same Zobo flavor as CNC\'s bottled drink, in a tea-bag format. Price pending confirmation.',
			'sku'         => 'CNC-ZOBO-TEA',
			'price'       => null,
			'category'    => 'CNC Beverages and Teas',
			'image'       => 'zobo-tea.png',
			'draft'       => true,
		),
	);

	foreach ( $products as $data ) {
		$product = new WC_Product_Simple();
		$product->set_name( $data['name'] );
		$product->set_description( $data['description'] );
		$product->set_short_description( $data['description'] );
		$product->set_sku( $data['sku'] );
		$product->set_status( ! empty( $data['draft'] ) ? 'draft' : 'publish' );
		$product->set_catalog_visibility( 'visible' );
		$product->set_manage_stock( false );
		$product->set_stock_status( 'instock' );

		if ( null !== $data['price'] ) {
			$product->set_regular_price( (string) $data['price'] );
			$product->set_price( (string) $data['price'] );
		}

		$term = get_term_by( 'name', $data['category'], 'product_cat' );
		if ( ! $term ) {
			$inserted = wp_insert_term( $data['category'], 'product_cat' );
			if ( ! is_wp_error( $inserted ) ) {
				$term_id = $inserted['term_id'];
			}
		} else {
			$term_id = $term->term_id;
		}
		if ( ! empty( $term_id ) ) {
			$product->set_category_ids( array( $term_id ) );
		}

		$product_id = $product->save();

		if ( $product_id ) {
			$attach_id = cnc_core_sideload_local_image(
				CNC_CORE_PATH . 'seed-assets/smartfoods/' . $data['image'],
				$product_id,
				$data['name']
			);
			if ( $attach_id ) {
				set_post_thumbnail( $product_id, $attach_id );
			}
		}
	}

	update_option( 'cnc_core_woocommerce_seeded', true );
}
add_action( 'admin_init', 'cnc_core_maybe_seed_woocommerce' );
