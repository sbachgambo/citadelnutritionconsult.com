<?php
/**
 * Seeds Services, Testimonials, FAQ, and Digital Products with the real
 * content gathered from the live source sites, so the site isn't empty
 * on first activation. Runs once; staff can freely edit/delete afterward
 * via the normal wp-admin screens — this never re-runs once it has.
 */

defined( 'ABSPATH' ) || exit;

function cnc_core_sideload_local_image( $file_path, $parent_post_id = 0, $title = '' ) {
	if ( ! file_exists( $file_path ) ) {
		return 0;
	}

	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';

	$filetype   = wp_check_filetype( basename( $file_path ), null );
	$upload_dir = wp_upload_dir();
	$dest_path  = $upload_dir['path'] . '/' . wp_unique_filename( $upload_dir['path'], basename( $file_path ) );

	if ( ! copy( $file_path, $dest_path ) ) {
		return 0;
	}

	$attachment_id = wp_insert_attachment(
		array(
			'post_mime_type' => $filetype['type'],
			'post_title'     => $title ? $title : sanitize_file_name( basename( $file_path ) ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		),
		$dest_path,
		$parent_post_id
	);

	if ( is_wp_error( $attachment_id ) || ! $attachment_id ) {
		return 0;
	}

	$attachment_data = wp_generate_attachment_metadata( $attachment_id, $dest_path );
	wp_update_attachment_metadata( $attachment_id, $attachment_data );

	return $attachment_id;
}

function cnc_core_seed_content() {
	if ( get_option( 'cnc_core_seeded' ) ) {
		return;
	}

	$services = array(
		array(
			'title'   => 'One-on-One Consultations',
			'content' => 'Personalized guidance from Dietitian Michelle (RDN) — customized meal planning and ongoing support, in-clinic or virtual via Zoom, Google Meet, or WhatsApp video.',
			'icon'    => 'consultations.png',
		),
		array(
			'title'   => 'Diet Plans & E-Books',
			'content' => 'Expertly designed nutrition guides tailored to specific goals — weight loss, chronic condition management, meal planning, grocery shopping, and mindful eating.',
			'icon'    => 'diet-plans.png',
		),
		array(
			'title'   => 'Online Courses',
			'content' => 'In-depth video lessons and interactive modules on nutrition, healthy eating, and management of diet-related conditions — self-paced.',
			'icon'    => 'online-courses.png',
		),
		array(
			'title'   => 'Culinary Services',
			'content' => 'Personalized diet calculation and hands-on cooking instruction — recipe modification and meal adaptation for diabetes, weight loss, cancer, kidney disease, and heart conditions while preserving cultural authenticity.',
			'icon'    => 'culinary.png',
		),
		array(
			'title'   => 'Corporate & Hospital Partnerships',
			'content' => 'Workplace wellness programs and Medical Nutrition Therapy services for healthcare facilities and gyms without an in-house registered dietitian.',
			'icon'    => 'corporate-hospital.png',
		),
		array(
			'title'   => 'Group Coaching & Follow-Up',
			'content' => 'Community-based support for clients with shared goals, plus dietitian-led progress monitoring and accountability check-ins.',
			'icon'    => 'group-coaching.png',
		),
		array(
			'title'   => 'Therapeutic Food Packs',
			'content' => 'Personalized meal packages for chronic illness recovery, digestive disorders, and weight management — from CNC Smart Foods.',
			'icon'    => 'therapeutic-food.png',
		),
		array(
			'title'   => 'Guest Speaking',
			'content' => 'Educational presentations on nutrition for community events, churches, corporate gatherings, and hospitals.',
			'icon'    => 'guest-speaking.png',
		),
		array(
			'title'   => 'Nutritional Assessment',
			'content' => 'Blood pressure and blood sugar testing plus a BMI checkup — the starting point for any personalized plan.',
			'icon'    => 'nutritional-assessment.png',
		),
	);

	foreach ( $services as $order => $service ) {
		$post_id = wp_insert_post(
			array(
				'post_type'    => 'service',
				'post_title'   => $service['title'],
				'post_content' => $service['content'],
				'post_excerpt' => $service['content'],
				'post_status'  => 'publish',
				'menu_order'   => $order,
			)
		);
		if ( $post_id && ! is_wp_error( $post_id ) ) {
			$attach_id = cnc_core_sideload_local_image(
				CNC_CORE_PATH . 'seed-assets/services/' . $service['icon'],
				$post_id,
				$service['title'] . ' icon'
			);
			if ( $attach_id ) {
				set_post_thumbnail( $post_id, $attach_id );
			}
		}
	}

	$testimonials = array(
		array( 'author' => 'Paul Masok', 'quote' => 'Ideal for good health and wellness — I lost weight without any complications.' ),
		array( 'author' => 'Anastesia Adamu', 'quote' => 'I appreciated the detailed and simple explanations — I finally learned how to read food labels properly.' ),
		array( 'author' => 'Saheed Badru', 'quote' => 'The team was courteous and professional throughout — genuinely supportive.' ),
		array( 'author' => 'Precious Makachi', 'quote' => 'I saw real changes in my body after following the diet plan — it wasn\'t easy at first, but it worked.' ),
	);

	foreach ( $testimonials as $testimonial ) {
		wp_insert_post(
			array(
				'post_type'    => 'testimonial',
				'post_title'   => $testimonial['author'],
				'post_content' => $testimonial['quote'],
				'post_excerpt' => $testimonial['quote'],
				'post_status'  => 'publish',
			)
		);
	}

	$faqs = array(
		array(
			'question' => 'What makes Citadel Nutrition Consult different?',
			'answer'   => 'We combine clinical expertise with practical culinary application — teaching cooking techniques and habit-building within African and Nigerian dietary contexts, rather than just handing out meal plans.',
		),
		array(
			'question' => 'Do you offer virtual consultations?',
			'answer'   => 'Yes — we serve clients worldwide via Zoom, Google Meet, and WhatsApp video calls, with the same value as an in-person appointment.',
		),
		array(
			'question' => 'How much does a consultation cost?',
			'answer'   => 'Initial sessions start at ₦15,000 (includes assessment and a personalized nutrition strategy); follow-ups are ₦7,000. See the Services page for full package pricing.',
		),
		array(
			'question' => 'Can you help with diabetes or hypertension?',
			'answer'   => 'Chronic disease management is a primary focus — we coordinate with your existing healthcare providers on evidence-supported protocols for metabolic control.',
		),
		array(
			'question' => 'Do you accommodate dietary restrictions?',
			'answer'   => 'Yes — plans are fully personalized to your preferences, cultural background, religious requirements, and any food allergies.',
		),
	);

	foreach ( $faqs as $order => $faq ) {
		wp_insert_post(
			array(
				'post_type'    => 'faq_item',
				'post_title'   => $faq['question'],
				'post_content' => $faq['answer'],
				'post_status'  => 'publish',
				'menu_order'   => $order,
			)
		);
	}

	$digital_products = array(
		array(
			'title'   => "A Dietitian's Secret To A Sustainable Weight Loss (28-Day Challenge)",
			'excerpt' => 'The 28-Day Challenge box set — 6 booklets: Setting Goals, What Makes Me Eat?, Medical Conditions & Weight Loss, Why Am I Overweight?, and How To Lose Weight I & II.',
			'price'   => 'Price TBC',
			'image'   => '28-day-challenge.jpg',
			// Not found under this name in the live Selar storefront listing (checked 2026-08-26) — confirm with Michelle whether it's still sold as a bundle.
			'url'     => 'https://dietitianmichelle.selar.com/',
		),
		array(
			'title'   => 'Eat and Don\'t Eat: Secrets To Staying Healthy',
			'excerpt' => 'Secrets to staying healthy.',
			'price'   => '₦2,000 (was ₦4,000)',
			'image'   => 'eat-and-dont-eat.jpg',
			// Not found in the live Selar storefront listing (checked 2026-08-26) — the ₦2,000 price came from an Instagram promo graphic, may be outdated.
			'url'     => 'https://dietitianmichelle.selar.com/',
		),
		array(
			'title'   => 'Cancer Diet',
			'excerpt' => 'Nutritional guidance for cancer patients and caregivers.',
			'price'   => '₦5,000 (was ₦10,000)',
			'image'   => 'cancer-diet.jpg',
			'url'     => 'https://dietitianmichelle.selar.com/cancer-diet-plan',
		),
		array(
			'title'   => 'Diet in Diabetes Treatment',
			'excerpt' => 'Managing blood sugar through everyday food choices.',
			'price'   => '₦5,000 (was ₦10,000)',
			'image'   => 'diabetes-treatment.jpg',
			'url'     => 'https://dietitianmichelle.selar.com/diabetes-diet-plan',
		),
		array(
			'title'   => 'What To Eat To Treat Hypertension',
			'excerpt' => 'A practical guide to blood-pressure-friendly eating.',
			'price'   => '₦5,000 (was ₦10,000)',
			'image'   => 'hypertension.jpg',
			'url'     => 'https://dietitianmichelle.selar.com/hypertension-diet-plan',
		),
		array(
			'title'   => 'Kidneys & Their Treatment Diet',
			'excerpt' => 'Nutrition guidance for kidney health and renal care.',
			'price'   => '₦5,000 (was ₦10,000)',
			'image'   => 'kidneys.jpg',
			'url'     => 'https://dietitianmichelle.selar.com/kidney-diet-plan',
		),
		array(
			'title'   => 'How To Eat And Gain Weight',
			'excerpt' => 'A guide for healthy, sustainable weight gain.',
			'price'   => '₦5,000 (was ₦10,000)',
			'image'   => 'gain-weight.jpg',
			'url'     => 'https://dietitianmichelle.selar.com/weight-gain-diet-plan',
		),
		array(
			'title'   => 'Weight Loss Diet Made Easy',
			'excerpt' => 'Practical, sustainable steps to lose weight.',
			'price'   => '₦5,000 (was ₦10,000)',
			'image'   => 'weight-loss-easy.jpg',
			// Live storefront lists a "Fast Track Weight Loss Diet Plan" at this price/slug — plausibly the same product under a different cover title, not confirmed, so left generic.
			'url'     => 'https://dietitianmichelle.selar.com/',
		),
		array(
			'title'   => 'Healing Recipe E-Book',
			'excerpt' => '50+ healthy, healing recipes to reduce meal-planning stress. Bundled with a diet plan, nutritional guidelines, and lifetime support access via a private Telegram group.',
			'price'   => '₦8,000 (was ₦20,000)',
			'image'   => 'healing-recipes.jpg',
			'url'     => 'https://dietitianmichelle.selar.com/healingrecipe',
		),
		array(
			'title'   => 'CNC Refresh Brochure',
			'excerpt' => 'A free downloadable brochure featuring CNC\'s meal options — a no-risk way to try before you buy.',
			'price'   => 'Free',
			// No cover image on file yet — upload one via the editor sidebar before launch.
			'image'   => '',
			'url'     => 'https://dietitianmichelle.selar.com/1wk361',
		),
	);

	foreach ( $digital_products as $order => $product ) {
		$post_id = wp_insert_post(
			array(
				'post_type'    => 'digital_product',
				'post_title'   => $product['title'],
				'post_content' => $product['excerpt'],
				'post_excerpt' => $product['excerpt'],
				'post_status'  => 'publish',
				'menu_order'   => $order,
			)
		);
		if ( $post_id && ! is_wp_error( $post_id ) ) {
			update_post_meta( $post_id, 'cnc_price', $product['price'] );
			update_post_meta( $post_id, 'cnc_selar_url', $product['url'] );

			if ( ! empty( $product['image'] ) ) {
				$attach_id = cnc_core_sideload_local_image(
					CNC_CORE_PATH . 'seed-assets/digital-products/' . $product['image'],
					$post_id,
					$product['title'] . ' cover'
				);
				if ( $attach_id ) {
					set_post_thumbnail( $post_id, $attach_id );
				}
			}
		}
	}

	$blog_posts = array(
		array(
			'title'   => 'Finger Foods: Fast, Easy and Convenient',
			'excerpt' => 'Finger foods are convenient, bite-sized snacks you can eat without making a mess — energy-dense, and easy to pair with protein and vegetables for a balanced meal.',
			'source'  => 'https://www.dietitianmichelle.com/what-are-finger-foods/',
			'image'   => 'finger-foods.jpeg',
		),
		array(
			'title'   => 'Intermittent Fasting and Weight Loss: The Ultimate Guideline',
			'excerpt' => 'A practical guide to using intermittent fasting for weight loss — eating-window strategies, portion control, and pairing it with regular activity.',
			'source'  => 'https://www.dietitianmichelle.com/intermittent-fasting/',
			'image'   => 'intermittent-fasting.jpg',
		),
		array(
			'title'   => 'Heart Health: Optimizing it Through Nutrition',
			'excerpt' => 'Why proper nutrition is a cornerstone of cardiovascular health, and the lifestyle changes — diet, activity, stress management — that support it.',
			'source'  => 'https://www.dietitianmichelle.com/heart-health-optimizing-it-through-nutrition/',
			'image'   => 'heart-health.jpeg',
		),
	);

	foreach ( $blog_posts as $post ) {
		$post_id = wp_insert_post(
			array(
				'post_type'    => 'post',
				'post_title'   => $post['title'],
				'post_content' => $post['excerpt'] . "\n\n<!-- Migrated from dietitianmichelle.com — full article text still needs pulling in from: " . $post['source'] . ' -->',
				'post_excerpt' => $post['excerpt'],
				'post_status'  => 'publish',
			)
		);
		if ( $post_id && ! is_wp_error( $post_id ) ) {
			$attach_id = cnc_core_sideload_local_image(
				CNC_CORE_PATH . 'seed-assets/blog/' . $post['image'],
				$post_id,
				$post['title']
			);
			if ( $attach_id ) {
				set_post_thumbnail( $post_id, $attach_id );
			}
		}
	}

	update_option( 'cnc_core_seeded', true );
}
