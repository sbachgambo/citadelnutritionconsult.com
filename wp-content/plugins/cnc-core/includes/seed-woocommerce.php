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
	if ( ! class_exists( 'WooCommerce' ) || ! function_exists( 'wc_get_product' ) ) {
		return;
	}

	// All seeded prices are Naira figures — WooCommerce defaults its store
	// currency to USD, which would silently mislabel every price. Set it
	// once; if a store admin later changes it, we don't fight that choice.
	if ( 'NGN' !== get_option( 'woocommerce_currency' ) && ! get_option( 'cnc_core_currency_set' ) ) {
		update_option( 'woocommerce_currency', 'NGN' );
		update_option( 'cnc_core_currency_set', true );
	}

	// WooCommerce 8.9+ ships new installs with "Coming soon" mode on, which
	// shows a "launching soon" placeholder on the Shop page (and every WC
	// page) instead of real products — an easy thing to forget to turn off
	// before launch. Turn it off once the real catalog is seeded; if a store
	// admin re-enables it deliberately later, we don't fight that choice.
	if ( 'yes' === get_option( 'woocommerce_coming_soon' ) && ! get_option( 'cnc_core_coming_soon_disabled' ) ) {
		update_option( 'woocommerce_coming_soon', 'no' );
		update_option( 'cnc_core_coming_soon_disabled', true );
	}

	if ( get_option( 'cnc_core_woocommerce_seeded' ) ) {
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
			'description' => 'Herbal tea bags positioned for general wellness and detox support. No preservatives or artificial color. Available in Jos only.',
			'sku'         => 'CNC-MORINGA-TEA',
			'price'       => 4800,
			'category'    => 'CNC Beverages and Teas',
			'image'       => 'moringa.png',
		),
		array(
			'name'        => 'CNC Cinnamon (Tea Bags)',
			'description' => 'Metabolic-balance tea bags, positioned within CNC\'s blood-sugar-friendly beverage range. Available in Jos only.',
			'sku'         => 'CNC-CINNAMON-TEA',
			'price'       => 5400,
			'category'    => 'CNC Beverages and Teas',
			'image'       => 'cinnamon.png',
		),
		array(
			'name'        => 'CNC Zobo Drink (50cl)',
			'description' => 'Bottled natural hibiscus beverage. No artificial flavour, no preservative. Available in Jos only.',
			'sku'         => 'CNC-ZOBO-DRINK-50CL',
			'price'       => 1000,
			'category'    => 'CNC Beverages and Teas',
			'image'       => 'zobo-drink.png',
		),
		array(
			'name'        => 'CNC Tigernut Drink (50cl)',
			'description' => 'Sweetened with sugar, 100% natural, no artificial flavour or preservative. Available in Jos only.',
			'sku'         => 'CNC-TIGERNUT-50CL',
			'price'       => 1500,
			'category'    => 'CNC Beverages and Teas',
			'image'       => 'tigernut.png',
		),
		// Prices confirmed 2026-09-01 by paging through the full live shop
		// catalog (cncsmartfood.com/shop/page/2/ and /page/3/) — previously
		// seeded as unpriced drafts because they weren't found on page 1.
		array(
			'name'        => 'Finger Millet Flour (900g) — "Tamba"',
			'description' => 'Finger millet with ginger and cloves, suitable for pap.',
			'sku'         => 'CNC-FINGER-MILLET-900',
			'price'       => 3600,
			'category'    => 'CNC Products',
			'image'       => 'finger-millet.png',
		),
		array(
			'name'        => 'Plantain Smartmix (1kg)',
			'description' => 'Plantain-based blend for a quick, wholesome swallow.',
			'sku'         => 'CNC-PLANTAIN-SMARTMIX-1KG',
			'price'       => 4200,
			'category'    => 'CNC Products',
			'image'       => 'plantain-smartmix.png',
		),
		array(
			'name'        => 'Tom Brown (1kg)',
			'description' => 'Guinea corn, soybeans, groundnut, and sugar — a traditional Nigerian pap.',
			'sku'         => 'CNC-TOM-BROWN-1KG',
			'price'       => 3600,
			'category'    => 'CNC Products',
			'image'       => 'tom-brown.png',
		),
		array(
			'name'        => 'CNC Zobo Infusion (Tea Bags)',
			'description' => 'Hibiscus tea bags — the same Zobo flavor as CNC\'s bottled drink, in a tea-bag format. Available in Jos only.',
			'sku'         => 'CNC-ZOBO-INFUSION-TEA',
			'price'       => 4800,
			'category'    => 'CNC Beverages and Teas',
			'image'       => 'zobo-tea.png',
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
