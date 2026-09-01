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

/**
 * Turns a flat array of HTML fragments (plain paragraph text, or a
 * '<h2>...</h2>' / '<ul><li>...</li>...</ul>' / '<ol>...</ol>' fragment)
 * into real Gutenberg block markup, so migrated article bodies are edit
 * ready in the block editor rather than one opaque Classic block.
 */
function cnc_core_blockify_body( array $chunks ) {
	$out = array();
	foreach ( $chunks as $chunk ) {
		if ( preg_match( '/^<h([2-4])>(.*)<\/h\1>$/s', $chunk, $m ) ) {
			$out[] = '<!-- wp:heading {"level":' . $m[1] . '} -->';
			$out[] = '<h' . $m[1] . '>' . $m[2] . '</h' . $m[1] . '>';
			$out[] = '<!-- /wp:heading -->';
		} elseif ( preg_match( '/^<(ul|ol)>(.*)<\/\1>$/s', $chunk, $m ) ) {
			$tag      = $m[1];
			$attrs    = 'ol' === $tag ? ' {"ordered":true}' : '';
			$inner    = preg_replace( '/<li>/', '<!-- wp:list-item --><li>', $m[2] );
			$inner    = preg_replace( '/<\/li>/', '</li><!-- /wp:list-item -->', $inner );
			$out[]    = '<!-- wp:list' . $attrs . ' -->';
			$out[]    = '<' . $tag . '>' . $inner . '</' . $tag . '>';
			$out[]    = '<!-- /wp:list -->';
		} else {
			$out[] = '<!-- wp:paragraph -->';
			$out[] = '<p>' . $chunk . '</p>';
			$out[] = '<!-- /wp:paragraph -->';
		}
	}
	return implode( "\n\n", $out );
}

function cnc_core_seed_content() {
	if ( get_option( 'cnc_core_seeded' ) ) {
		return;
	}

	$services = array(
		array(
			'title'   => 'One-on-One Consultations',
			'content' => 'Customized guidance from a registered dietitian — tailored meal planning and ongoing support, in-clinic or virtual via Zoom, Google Meet, or WhatsApp video.',
			'icon'    => 'consultations.jpg',
		),
		array(
			'title'   => 'Diet Plans & E-Books',
			'content' => 'Expertly designed nutrition guides tailored to specific goals — weight loss, chronic condition management, meal planning, grocery shopping, and mindful eating.',
			'icon'    => 'diet-plans.jpg',
		),
		array(
			'title'   => 'Online Courses',
			'content' => 'In-depth video lessons and interactive modules on nutrition, healthy eating, and management of diet-related conditions — self-paced.',
			'icon'    => 'online-courses.jpg',
		),
		array(
			'title'   => 'Culinary Services',
			'content' => 'Customized diet calculation and hands-on cooking instruction — recipe modification and meal adaptation for diabetes, weight loss, cancer, kidney disease, and heart conditions while preserving cultural authenticity.',
			'icon'    => 'culinary.jpg',
		),
		array(
			'title'   => 'Corporate & Hospital Partnerships',
			'content' => 'Workplace wellness programs and Medical Nutrition Therapy services for healthcare facilities and gyms without an in-house registered dietitian.',
			'icon'    => 'corporate-hospital.jpg',
		),
		array(
			'title'   => 'Group Coaching & Follow-Up',
			'content' => 'Community-based support for clients with shared goals, plus dietitian-led progress monitoring and accountability check-ins. ₦50,000 for a 30-day accountability program (daily follow-ups, meal reviews, weekly sessions).',
			'icon'    => 'group-coaching.jpg',
		),
		array(
			'title'   => 'Therapeutic Food Packs',
			'content' => 'Customized meal packages for chronic illness recovery, digestive disorders, and weight management — from CNC Smart Foods.',
			'icon'    => 'therapeutic-food.jpg',
		),
		array(
			'title'   => 'Guest Speaking',
			'content' => 'Educational presentations on nutrition for community events, churches, corporate gatherings, and hospitals.',
			'icon'    => 'guest-speaking.jpg',
		),
		array(
			'title'   => 'Nutritional Assessment',
			'content' => 'Blood pressure and blood sugar testing plus a BMI checkup — the starting point for any customized plan.',
			'icon'    => 'nutritional-assessment.jpg',
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
				$service['title'] . ' photo'
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
			'answer'   => 'There are two consultation types: Consultation (₦15,000) includes a nutritional assessment, a 30–45 minute session, and a 7-day general diet plan; Customized Plan (₦30,000) adds a fully customized 7-day diet plan with a shopping list, ready 5–7 working days after your session. Follow-up sessions are ₦7,500. See the Services page for full package pricing.',
		),
		array(
			'question' => 'Can you help with diabetes or hypertension?',
			'answer'   => 'Chronic disease management is a primary focus — we coordinate with your existing healthcare providers on evidence-supported protocols for metabolic control.',
		),
		array(
			'question' => 'Do you accommodate dietary restrictions?',
			'answer'   => 'Yes — plans are fully customized to your preferences, cultural background, religious requirements, and any food allergies.',
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
			// Re-checked 2026-09-01 by paging through the full 40-item live Selar
			// storefront (both listing pages) — still not sold under this title
			// or as a bundle. Confirm with Michelle whether it's still offered.
			'url'     => 'https://dietitianmichelle.selar.com/',
		),
		array(
			'title'   => 'Eat and Don\'t Eat: Secrets To Staying Healthy',
			'excerpt' => 'Secrets to staying healthy.',
			'price'   => '₦2,000 (was ₦4,000)',
			'image'   => 'eat-and-dont-eat.jpg',
			// Re-checked 2026-09-01 by paging through the full 40-item live Selar
			// storefront (both listing pages) — still not found under this title.
			// The ₦2,000 price came from an Instagram promo graphic and may be outdated.
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
			// Confirmed 2026-09-01: the live storefront's "Fast Track Weight Loss
			// Diet Plan" is priced identically (₦5,000, was ₦10,000) — almost
			// certainly this e-book under its storefront listing title.
			'url'     => 'https://dietitianmichelle.selar.com/-weight-loss-diet-plan',
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
			'body'    => array(
				'<h2>What Are Finger Foods?</h2>',
				'Finger foods are small, individual portions of food that you can eat without making a mess. Think of them as bite-sized snacks that don\'t require utensils, perfect for when you\'re on the go or just not in the mood for a full meal. Examples include:',
				'<ul><li>Chips</li><li>Baked sweet potato</li><li>Masa (rice cake)</li><li>Toasted bread</li></ul>',
				'These foods are particularly helpful for clients who don\'t have time to eat or prefer to eat smaller, more frequent meals throughout the day.',
				'<h2>Benefits</h2>',
				'Finger foods are energy-dense, meaning you get a lot of calories and nutrients in a small amount. To create a balanced finger food meal, pair these foods with protein and vegetables. Here are some examples:',
				'<strong>1. Masa Meal</strong>',
				'<ul><li>2 balls of masa</li><li>180g grilled chicken</li><li>Lettuce or cabbage</li></ul>',
				'<strong>2. Sweet Potato Chips Meal</strong>',
				'<ul><li>90–120g sweet potato chips</li><li>180g grilled fish and sauce</li><li>Steamed veggies</li></ul>',
				'<strong>3. Moi-Moi Meal</strong>',
				'<ul><li>1 wrap moi-moi</li><li>3 boiled eggs (remove 2 yolks)</li><li>Coleslaw</li></ul>',
				'<h2>How to Manage Caloric Intake with Finger Foods</h2>',
				'To maintain a balanced diet, aim for meals that provide between 500 to 700 calories. Depending on your eating habits and mood, you can enjoy finger foods for lunch or dinner.',
				'<h2>Tips for Healthier Choices</h2>',
				'When served snacks at business meetings, choose medium-sized snacks — opt for meat pie over doughnuts and cakes, and tea instead of soft drinks. If you frequently attend meetings, consider bringing healthy snacks like masa or other nutritious options.',
				'<h2>Finger Foods and Intermittent Fasting</h2>',
				'These foods work well with intermittent fasting:',
				'<ul><li>Morning: Water or sugar-free tea</li><li>Midday: Finger food meal of 500 to 700 calories</li><li>Evening: Balanced dinner of 800 to 1000 calories</li></ul>',
				'Thank you for your time and attention. I hope you now have a better understanding of finger foods and how to incorporate them into your diet. Your questions, comments, and contributions are always welcome!',
			),
		),
		array(
			'title'   => 'Intermittent Fasting and Weight Loss: The Ultimate Guideline',
			'excerpt' => 'A practical guide to using intermittent fasting for weight loss — eating-window strategies, portion control, and pairing it with regular activity.',
			'source'  => 'https://www.dietitianmichelle.com/intermittent-fasting/',
			'image'   => 'intermittent-fasting.jpg',
			'body'    => array(
				'Are you tired of struggling with weight loss diets that don\'t work? Intermittent fasting might be the solution you\'ve been looking for! In this comprehensive guide, I\'ll walk you through everything you need to know about intermittent fasting and how it can help you shed those extra pounds effectively.',
				'<h2>The Three Pillars of Weight Loss</h2>',
				'<ol><li>Low-Calorie Diet: Kickstart your weight loss journey with a well-calculated diet plan, aiming for around 1,500 calories per day.</li><li>Regular Exercise: Incorporate regular physical activity into your routine to boost metabolism and burn calories.</li><li>Active Lifestyle: Aim for 15,000 steps daily to maintain an active lifestyle and further enhance weight loss results.</li></ol>',
				'<h2>Understanding Intermittent Fasting</h2>',
				'It is a popular eating pattern that involves cycling between periods of eating and fasting. Here\'s how it works:',
				'<strong>Skip Breakfast:</strong> Start your day with water or tea and delay your first meal until noon.',
				'<strong>Eat Within a Window:</strong> Consume all your meals within a specified time frame, such as between 12 noon and 8:00pm.',
				'<strong>Fasting Period:</strong> Fast for the remaining hours, allowing your body to burn stored fat for energy.',
				'<h2>Intermittent Fasting Patterns</h2>',
				'<strong>16/8 Method:</strong> Fast for 16 hours and eat within an 8-hour window, starting from 12 noon to 8:00pm.',
				'<strong>18/6 Method:</strong> Extend the fasting period to 18 hours, eating between 2:00pm and 8:00pm.',
				'<h2>Tips for Successful Weight Loss</h2>',
				'<ol><li>Avoid Overeating: Maintain portion control during eating windows to maximize weight loss benefits.</li><li>Prioritize Dinner: Make dinner your most substantial meal to fuel your body for the fasting period.</li><li>Stay Hydrated: Drink plenty of water or herbal tea during fasting periods to stay hydrated and curb hunger.</li></ol>',
				'<h2>Who Should Avoid Intermittent Fasting?</h2>',
				'While intermittent fasting is safe for many individuals, it may not be suitable for everyone. Avoid intermittent fasting if you are:',
				'<ul><li>Nursing mothers</li><li>Elderly individuals</li><li>Taking medication, especially in the morning</li><li>Prone to stomach ulcers</li></ul>',
				'<h2>Conclusion</h2>',
				'Intermittent fasting offers a simple yet effective approach to weight loss when combined with a balanced diet and regular exercise. If you have any questions or need further guidance, feel free to consult with Dietitian Michelle. Your health and well-being is my top priority.',
			),
		),
		array(
			'title'   => 'Heart Health: Optimizing it Through Nutrition',
			'excerpt' => 'Why proper nutrition is a cornerstone of cardiovascular health, and the lifestyle changes — diet, activity, stress management — that support it.',
			'source'  => 'https://www.dietitianmichelle.com/heart-health-optimizing-it-through-nutrition/',
			'image'   => 'heart-health.jpeg',
			'body'    => array(
				'<h2>Understanding Heart Health</h2>',
				'Heart health is a comprehensive term that encapsulates the overall well-being of the heart muscle and blood vessels. It\'s imperative to prioritize proper nutrition as a cornerstone for maintaining optimal heart health. Equally crucial are lifestyle modifications, such as adopting a balanced diet, engaging in regular physical activity, and effectively managing stress. These lifestyle changes play a pivotal role in positively influencing cardiovascular health and reducing the risk of heart-related issues.',
				'<h2>WHO Recommendations for Heart-Healthy Eating</h2>',
				'The World Health Organization recommends a balanced diet rich in fruits, vegetables, whole grains, lean proteins, and healthy fats. Limiting saturated fats, sugars, and sodium intake is vital. Incorporating fatty fish, whole grains, leafy greens, berries, nuts, and seeds into the diet promotes heart health by providing essential nutrients and antioxidants. These foods help reduce the risk of heart disease.',
				'<h2>Conclusion</h2>',
				'Prioritizing heart-healthy nutrition is vital for maintaining cardiovascular health and preventing heart disease. By adhering to WHO\'s dietary recommendations and integrating heart-healthy foods into our diets, we can optimize heart health and improve our overall well-being. For personalized guidance on heart-healthy eating habits and diet plans, consider booking a consultation with a registered dietitian.',
			),
		),
		array(
			'title'   => 'Some Time Ago I Explained What "Weight Plateau" Means and How Frustrating it Can Be',
			'excerpt' => 'Practical strategies to break through a weight-loss plateau — adjusting exercise frequency, eating normally, and resuming intensity when the scale stalls.',
			'source'  => 'https://www.dietitianmichelle.com/some-time-ago-i-explained-what-weight-plateau-means-and-how-frustrating-it-can-be/',
			'image'   => 'weight-plateau.jpeg',
			'body'    => array(
				'Sometime ago I explained what "weight plateau" means and how frustrating it can be. I\'ll briefly tell you how to break out of it today.',
				'Do these for a week:',
				'<ul><li>Cut down on your exercising. Exercise for only 3 times in a week.</li><li>Eat normally. Have your breakfast, lunch and dinner, especially if you were on an intermittent fasting programme or a diet that cuts down on your eating time.</li><li>Give yourself a weekend treat with your favorite food.</li><li>Relax. Do not bother checking your weight for weight gain or weight loss in this period.</li></ul>',
				'Immediately after a week\'s treat, resume a higher intensity exercise than what you were doing. Cut down on your calorie intake — follow a 1,200–1,500 calorie diet plan. Or resume your keto diet, if you were on one.',
				'Of course I seldom recommend keto diets for weight loss. But if you were on one and it had no adverse effects on your health, then resume it.',
				'Follow these simple steps. Your efforts, augmented with the right dose of exercising and calorie intake, I promise that your body will beat its state of weight plateau. Now go out and smash your health goals! See you on the other side.',
			),
		),
		array(
			'title'   => 'Saturdays are for Exercise!',
			'excerpt' => 'Why daily movement matters for weight-loss goals, with accessible home workout recommendations for beginners.',
			'source'  => 'https://www.dietitianmichelle.com/saturdays-are-for-exercise/',
			'image'   => 'saturdays-exercise.jpeg',
			'body'    => array(
				'No, scratch that! Every day is for exercise. For better results on your weight loss journey, you need to exercise as frequently as possible. And one more thing you need as you exercise is a guide. I recommend Leslie Sansone on YouTube — I have worked with her for about 10 years now. Yes, exercises can be strenuous, especially for starters, but Leslie Sansone makes workouts appear less cumbersome. And what\'s more, her routines are very much practicable from home.',
				'Try any of her workouts and thank me later. If you\'ve been having your workout sessions with her before now, let me know what you think. How\'s the journey been?',
			),
		),
		array(
			'title'   => 'Have You Heard about the Wonder Grain?',
			'excerpt' => 'An underused nutritious grain that supports glucose control, weight management, and healthy pregnancy.',
			'source'  => 'https://www.dietitianmichelle.com/have-you-heard-about-the-wonder-grain/',
			'image'   => 'wonder-grain-1.jpeg',
			'body'    => array(
				'Dietitians and health organizations have been trying to create awareness on the nutritional value of the wonder grain — a nutritious grain loaded with nutrients but about to be forgotten because of bread and rice.',
				'I\'ll tell you about the wonder grain: why it should be a part of your diet, how it can be part of your diet, and how it can help you achieve your health goals. Just stay tuned:',
				'<ul><li>Glucose control</li><li>Weight loss</li><li>Normal blood pressure</li><li>Healthy pregnancy</li><li>Optimal infant nutrition</li></ul>',
				'<strong>Did you know?</strong> Eating the wonder grain can help you achieve all of the above. At CNC, we have made it into products for your nutritional convenience.',
				'Call +234 904 675 8079 to place an order or visit www.cncsmartfoods.com for more.',
			),
		),
		array(
			'title'   => 'The Wonder Grain',
			'excerpt' => 'Introducing finger millet (tamba) — rich in fibre, antioxidants, methionine, and calcium.',
			'source'  => 'https://www.dietitianmichelle.com/the-wonder-grain/',
			'image'   => 'wonder-grain-2.webp',
			'body'    => array(
				'Have you heard of finger millet? It\'s a grain, in the same family as maize, guinea corn and millet. Finger millet (or tamba as it is also called) is grown in parts of Africa and Asia. Dietitians and nutritionists regard it as the wonder grain because of its enormous nutritional value.',
				'It is rich in:',
				'<ul><li>Dietary fibre — which makes it filling</li><li>Antioxidants — an important group of vitamins</li><li>Methionine — for enhanced skin health</li><li>Calcium — for strong bones</li></ul>',
				'Calcium is usually obtained from dairy products. But finger millet, though a cereal, supplies it generously. It is different from other kinds of millet because it is filling and is also a good source of essential nutrients.',
				'Would you like to try the wonder grain? Won\'t you love to join in the growing number of individuals reaping the healthy benefits of eating the wonder grain (tamba)?',
				'We have two types of Tamba products available. The first, Tamba Flour, is made from finger millet, ginger and cloves. It contains less fibre, making it suitable for babies and children generally — the pap is smooth and appropriate for children to consume.',
				'The second product is the Tamba Flour Spicy, made too from finger millet, ginger and cloves. The ginger content of this product is higher than in the other, and it also has a higher fibre content compared to the other product.',
				'You could place an order for either of these products. They are both available and we offer nationwide delivery. Call +234 904 675 8079 or visit www.cncsmartfoods.com.',
			),
		),
		array(
			'title'   => 'Hey You!!',
			'excerpt' => 'Tamba (finger millet) flour product highlights — flavor variations and ordering information.',
			'source'  => 'https://www.dietitianmichelle.com/hey-you/',
			'image'   => 'hey-you.png',
			'body'    => array(
				'Do you want to lose weight? Perhaps control your sugar level? Normalize your blood pressure? Strengthen your bones? Have healthy, glowing skin? Feed your child/ward rightly? Then you need to make Tamba a part of your daily diet.',
				'Tamba has a dark, chocolatey colour and a slightly nutty flavour. It is naturally delicious and can be prepared with a variety of supplements according to taste. Its health benefits are enormous — you just need to discover it for yourself.',
				'Orange juice in Tamba is a whole vibe!! Along with all the many nutritional advantages of the wonder grain, it comes with extra benefits:',
				'<ul><li>It improves the absorption of iron in the grain</li><li>It aids digestion</li><li>It enhances bioavailability of other nutrients</li></ul>',
				'And if you\'re one for more options, you can try Tamba with lemon. Lemon has an even higher vitamin C concentration than orange, and it aids healthy weight loss. As for the taste — it\'s just superb!',
				'Call +234 904 675 8079 to place an order or visit www.cncsmartfoods.com for more.',
			),
		),
		array(
			'title'   => 'Get Creative with the Wonder Grain!',
			'excerpt' => 'Versatile ways to prepare finger millet — pap, pancakes, flour for sauces, bread, and baked goods.',
			'source'  => 'https://www.dietitianmichelle.com/get-creative-with-the-wonder-grain/',
			'image'   => 'wonder-grain-3.webp',
			'body'    => array(
				'Did you know finger millet can be used in a variety of ways? At CNC, we\'ve made it into products that are more than healthy for your consumption.',
				'<ul><li>You can make it into delicious pap, adding sweeteners and/or milk to your taste</li><li>You can make your high-fibre, nutritious pancakes with it</li><li>You can thicken your sauce with its fine, smooth flour — a lot healthier than using refined wheat flour</li><li>You can bake your bread and make your chin chin, etc. with it</li></ul>',
				'What are you waiting for? Call +234 904 675 8079 or visit www.cncsmartfoods.com to place an order. We deliver nationwide.',
			),
		),
		array(
			'title'   => 'Weight Loss – Top Ten Facts',
			'excerpt' => 'Ten evidence-based principles for sustainable weight loss — calorie deficit, quality nutrition, consistency, sleep, and stress management.',
			'source'  => 'https://www.dietitianmichelle.com/weight-loss-top-ten-facts/',
			'image'   => 'weight-loss-facts.jpeg',
			'body'    => array(
				'Are you looking to shed those extra pounds and improve your health? Understanding the science behind weight loss can empower you to make informed decisions about your journey. Here are ten confirmed facts:',
				'<ol><li><strong>Calorie deficit is key.</strong> To lose weight, you must consume fewer calories than you burn — this principle remains the cornerstone of successful weight loss.</li><li><strong>Quality matters.</strong> Not all calories are created equal. Focus on nutrient-dense, high-fibre foods like vegetables, tubers, lean protein and whole grains.</li><li><strong>Exercise is essential.</strong> Regular physical activity not only burns calories but also improves overall health and aids in weight maintenance.</li><li><strong>Consistency is key.</strong> Sustainable weight loss requires consistency in healthy eating and exercise habits over time.</li><li><strong>Metabolism plays a role.</strong> Metabolism varies among individuals and can impact weight loss, though focusing solely on it may not be the most effective strategy.</li><li><strong>Hydration matters.</strong> Drinking plenty of water can aid weight loss by promoting satiety and supporting metabolic functions.</li><li><strong>Sleep and stress.</strong> Quality sleep and stress management are crucial, as they impact hormone regulation and appetite control.</li><li><strong>Mindful eating.</strong> Paying attention to portion sizes and practicing mindful eating can help prevent overeating.</li><li><strong>Support system.</strong> Having a supportive community or accountability partner increases adherence and boosts motivation — join the Dietitian Michelle Support Group on WhatsApp for questions and answers on nutrition and your health.</li><li><strong>Long-term focus.</strong> Sustainably losing weight is a marathon, not a sprint. Embrace gradual progress and focus on long-term health rather than quick fixes.</li></ol>',
				'<h2>Transform Your Body with an Online Course</h2>',
				'Ready to kickstart your weight loss journey? An online course can offer a personalized plan, expert guidance, and proven strategies — including a video tutorial on prevention and treatment, a pre-planned diet plan and nutritional guidelines, a bonus course on how to follow a diet plan, a bonus course on healthy foods like acha (fonio), a bonus recipe, and lifetime support in a private Telegram group. Don\'t wait to start your transformation.',
			),
		),
		array(
			'title'   => 'Food Labels: Should I Eat This?',
			'excerpt' => 'A video series breaking down packaged food labels so you can make informed dietary choices.',
			'source'  => 'https://www.dietitianmichelle.com/all-you-need-to-know-about-food-labels/',
			'image'   => 'food-labels.jpeg',
			'body'    => array(
				'Do you ever find yourself diving into a pack of biscuits without sparing a glance at the food label? Well, you\'re not alone — in Nigeria, tearing open a pack of goodies without a care for what\'s inside is practically a national pastime.',
				'But as much as we love diving straight into our snacks, it\'s worth paying a bit more attention to what we\'re putting into our bodies. That\'s where a video series called "Food Labels" comes in.',
				'Picture this: once a week, we take a favourite packaged treat and dissect its nutritional info like true food detectives. Knowing what\'s in your food is the first step to making healthier choices — and maybe even avoiding a few pesky allergies along the way.',
				'So, are you ready to join the snack revolution? Say goodbye to mindless munching and hello to informed eating — you might even impress your friends with your newfound food knowledge. Head over to the YouTube channel and subscribe for a treasure trove of food label breakdowns, "Should I Eat This?"',
				'Let\'s munch smart, Nigeria! You can also consult a dietitian for a diet plan that suits your nutrition and dieting goals.',
			),
		),
		array(
			'title'   => 'Exclusive Breastfeeding',
			'excerpt' => 'The nutritional and health benefits of exclusive breastfeeding for the first six months, and when supplementation may be necessary.',
			'source'  => 'https://www.dietitianmichelle.com/exclusive-breastfeeding/',
			'image'   => 'breastfeeding.jpeg',
			'body'    => array(
				'As a dietitian and lactation expert, I\'m excited to share the importance of exclusive breastfeeding for newborns. Research has shown that breastmilk provides everything a child needs from birth to six months, making it the ultimate source of nutrition.',
				'<h2>What is Exclusive Breastfeeding?</h2>',
				'This means feeding your newborn only breastmilk for the first six months of life, without any additional foods or liquids, including water.',
				'<h2>Why is it Recommended?</h2>',
				'<ol><li><strong>Meets baby\'s needs:</strong> breastmilk contains all the necessary nutrients, antibodies, and vitamins for optimal growth and development.</li><li><strong>Protects from infection:</strong> breastmilk\'s antibodies shield babies from infections and diseases.</li><li><strong>Economical:</strong> it is free, and mothers only need to stay hydrated and eat well to produce milk.</li><li><strong>Promotes bonding:</strong> skin-to-skin contact and breastfeeding foster a strong mother-child bond.</li><li><strong>No overfeeding worries:</strong> breastfed babies self-regulate their intake, reducing the risk of overfeeding.</li><li><strong>Low risk of illnesses:</strong> exclusive breastfeeding minimizes the risk of diarrhea, ear infections, and other diseases.</li><li><strong>Helps mother\'s recovery:</strong> breastfeeding aids in postpartum weight loss and uterine shrinkage.</li></ol>',
				'<h2>When is Exclusive Breastfeeding Not Recommended?</h2>',
				'<ol><li>Multiple births: supplement with infant formula if breastfeeding twins or multiples.</li><li>Mother\'s health issues: if the mother is unwell or unable to breastfeed.</li><li>Low milk supply: if the mother\'s milk production is insufficient to meet the baby\'s needs.</li><li>Transmittable diseases: in some cases, mothers with transmittable diseases may need to supplement with formula.</li><li>Insufficient breastmilk: if lactation experts determine that breastmilk is not enough for the baby.</li></ol>',
				'<h2>Conclusion</h2>',
				'Exclusive breastfeeding is a powerful tool for newborns, offering numerous benefits for both mother and baby. However, it\'s essential to understand the exceptions and consult with a lactation expert or healthcare professional if you have any concerns.',
			),
		),
		array(
			'title'   => 'Healthy Meal Ideas for School – Age Kids: A Dietitian\'s Perspective',
			'excerpt' => 'Balanced breakfast, lunch, and snack ideas for school-age kids, plus practical meal prep and storage tips.',
			'source'  => 'https://www.dietitianmichelle.com/school-child-nutrition/',
			'image'   => 'school-nutrition.jpeg',
			'body'    => array(
				'As a parent, ensuring your child eats a balanced diet can be challenging, especially during the school year. Dietitian Michelle shares her expertise on healthy meal ideas for school-age kids.',
				'<h2>Understanding Your Child\'s Needs</h2>',
				'Before diving into meal ideas, remember that every child is unique, and their dietary needs may vary — consider consulting a dietitian for a customized plan.',
				'<h2>Breakfast Options</h2>',
				'<ul><li>Acha (fonio) pudding with sugar and milk</li><li>Guinea-corn pap with sugar and milk</li><li>Cornflakes with sugar and milk</li><li>Finger millet (tamba) with sugar and milk</li><li>Oat pudding with sugar and milk</li></ul>',
				'<h2>School Meals and Snacks</h2>',
				'Cooked meals by 10:00 a.m. and snacks by 12:00 p.m. Pack healthy options like:',
				'<ul><li>Rice and stew</li><li>Yam and egg sauce</li><li>Pasta</li><li>Jollof or fried rice</li><li>Bean pottage or swallows</li></ul>',
				'<h2>Healthy Snack Ideas</h2>',
				'<ul><li>Natural drinks: zobo, kunu, pineapple juice</li><li>Snacks: plantain chips, Irish potato chips, sweet potato chips, kuli-kuli, peanut brittle, etc.</li></ul>',
				'<h2>Home Snacks and Dinner</h2>',
				'Light snacks like rice masa and tea, or acha (fonio) masa and yogurt; dinner as a balanced meal with calcium-rich foods and fruits/vegetables.',
				'<h2>Tips and Reminders</h2>',
				'<ul><li>Preserve and reheat food properly to avoid spoilage and poisoning</li><li>Consider your child\'s stress level and adjust meal plans accordingly</li><li>Limit processed foods and choose options with minimal preservatives</li></ul>',
			),
		),
		array(
			'title'   => 'The Truth About Kpomo (Cow Skin): Nutrition and Health Concerns',
			'excerpt' => 'The nutritional composition of kpomo and the health risks tied to how it\'s processed.',
			'source'  => 'https://www.dietitianmichelle.com/i-hate-to-say-goodbye-dear-kpomo/',
			'image'   => 'kpomo.jpeg',
			'body'    => array(
				'In West Africa, particularly Nigeria, kpomo (cow skin, or ponmo) is consumed extensively. Nigerians prepare it by boiling, burning, or smoking cow hides to make it edible. The food is popular because it\'s affordable, accessible, and versatile — as a side dish with jollof rice, garri, or fufu; as a seasoned snack with spices and herbs; and as a seasoning ingredient in soups, stews, and sauces. Its prevalence stems from cultural value, affordability, and culinary versatility.',
				'<h2>Nutritional Value</h2>',
				'Per 100g of boiled, thick cow skin: 224.65kcal of energy, 46.9g of protein, 43.9g of water, 6.80g of carbohydrate, 1.09g of fat, and 0.02g of fibre, plus small amounts of calcium, iron, magnesium, phosphorus, and zinc.',
				'<h2>Limitations and Health Concerns</h2>',
				'Although kpomo contains collagen, it lacks essential amino acids. The burning preparation method raises significant concerns, as it releases Polycyclic Aromatic Hydrocarbons (PAHs), which increase cancer risk.',
				'<h2>Healthier Alternatives to Kpomo</h2>',
				'Consider fish, local chicken, turkey, and quail as nutritious protein sources instead.',
				'<h2>Conclusion</h2>',
				'While kpomo contains some nutrients, they are non-essential given the health risks of how it\'s processed — it\'s worth prioritizing health by exploring healthier options.',
			),
		),
		array(
			'title'   => 'How to Prepare Healthy Food for the New Year: Stock Up Smart',
			'excerpt' => 'Stocking up on nutrient-dense, preservative-free staples — rice, beans, acha, and finger millet — for long-term storage.',
			'source'  => 'https://www.dietitianmichelle.com/stock-up-smart-how-to-prepare-healthy-food-for-the-new-year/',
			'image'   => 'stock-up-new-year.jpeg',
			'body'    => array(
				'As the new year draws in, it becomes necessary to create time to think about healthy food choices and how that can be maintained throughout the year. One important way is through stockpiling nutritionally healthy foods free of chemicals and preservatives.',
				'<h2>The Problem with Chemically Preserved Food</h2>',
				'Around the holiday season, farmers and business people start buying food in bulk and storing it for long periods, sometimes using chemical-based preservation that may pose serious health issues in the long run — our kidneys and liver cannot bear this heavy burden of chemicals.',
				'<h2>Natural Healthy Food Storage Methods</h2>',
				'At CNC Smartfoods, food is stored without chemicals using a simple, effective method: food is put in a nylon bag and then in a PIC bag, which allows it to stay fresh for up to a year. Other natural storage methods include beans stored in a 25-litre gallon with pepper, and airtight containers or deep-freezer storage.',
				'<h2>Stocking Up on Healthy Food</h2>',
				'Foods worth stocking up on include:',
				'<ol><li>Rice</li><li>Beans</li><li>Acha</li><li>Finger millet (tamba)</li></ol>',
				'These foods are rich in nutrients and can be stored for long periods without spoiling.',
				'<h2>Consult with a Dietitian</h2>',
				'If you have any other questions about healthy food storage or would like to know more about maintaining a balanced diet, consult with a dietitian today. My sincere hope is that this article has inspired you to empower your food choices and bring the new year off just right. Happy cooking!',
			),
		),
		array(
			'title'   => 'Healthy Christmas',
			'excerpt' => 'Enjoying traditional Nigerian Christmas dishes like jollof rice and pounded yam with healthier swaps and portion control.',
			'source'  => 'https://www.dietitianmichelle.com/healthy-christmas/',
			'image'   => 'healthy-christmas.jpeg',
			'body'    => array(
				'The festive season in Nigeria is all about family, fun, and — let\'s be honest — food. The only problem is that with all the deliciousness around, keeping a balanced diet isn\'t easy. But you can have fun and make healthy Christmas meals without feeling like you\'re missing out! Here\'s how:',
				'<h2>Healthy Christmas Jollof Rice</h2>',
				'As much as Jollof rice at Christmas is the king of Nigerian food, white rice can sneak up on you. Swap it with brown rice, which is full of fibre and should keep you fuller for longer. Add lots of veggies like carrots, peas, and bell peppers for a nutritional overhaul.',
				'<h2>Healthier Pounded Yam</h2>',
				'Pounded yam is a festive favourite, but those calories! Try substituting it with acha or cassava flour, which has fewer calories, is gluten-free, and will still be as filling.',
				'<h2>Healthy Christmas Pepper Soup</h2>',
				'Make pepper soup a little healthier by choosing lean meats like chicken or fish. Throw in veggies for vitamins and antioxidants — carrots, onions, and tomatoes — and go easy on the oil for a lighter broth.',
				'<h2>Portion Control Is Key</h2>',
				'You don\'t need to avoid your favourite dishes, just keep the portions in check. Fill half your plate with veggies, lean proteins, and whole grains, and enjoy the goodness without the guilt.',
				'<h2>Hydrate and Choose Lighter Drinks</h2>',
				'Ditch the sugary sodas and say yes to homemade fruit juices or infused water. Watermelon, pineapple, and mint-infused water are refreshing, low-calorie options that will keep you feeling merry and bright without packing on the pounds.',
				'Enjoy your Christmas feast guilt-free!',
			),
		),
		array(
			'title'   => 'Unripe Plantain: A Good Source of Iron?',
			'excerpt' => 'Clearing up a common myth — unripe plantain isn\'t a major iron source, but it does offer fibre, vitamin A, and vitamin B6.',
			'source'  => 'https://www.dietitianmichelle.com/unripe-plantain-a-good-source-of-iron/',
			'image'   => 'unripe-plantain.jpg',
			'body'    => array(
				'Unripe plantain has a lot to offer in nutrients and versatility. It\'s a meal favourite for any diet, and can easily be prepared by boiling, frying, roasting, mashing, or even grilling.',
				'<h2>Debunking the Iron Myth</h2>',
				'The thought that plantains are rich in iron probably comes from the green colour of the raw peel — which isn\'t eaten and so doesn\'t add to the plantain\'s nutritional value. In some foods, like leafy vegetables, green colour can indicate high iron content, but that\'s not the case with plantains.',
				'<h2>The Real Nutritional Value of Unripe Plantains</h2>',
				'So what do raw, green, unripe plantains actually provide? They\'re abundant in:',
				'<ul><li><strong>Fibre:</strong> supports proper digestion and intestinal health</li><li><strong>Vitamin A:</strong> good for vision, immunity, and skin condition</li><li><strong>Vitamin B6:</strong> participates in the synthesis of serotonin and norepinephrine, the neurotransmitters that help regulate mood</li></ul>',
				'<h2>The Importance of Iron in Your Diet</h2>',
				'Potassium helps control blood pressure and supports muscle and nerve function; magnesium is crucial for bone health, energy production, and a healthy heart. But plantains are not a source of iron when consumed unripe — to boost iron intake, make red meat, spinach and other leafy vegetables, beans, fortified cereals, and seafood a key part of your diet.',
				'<h2>Making Informed Choices About Your Diet</h2>',
				'Knowing the nutritional value of foods like unripe plantains lets you make wiser dietary choices. Unripe plantains also contain very low sugar levels compared to ripe ones, making them good for blood-sugar-conscious eaters or anyone following a low-glycemic-index diet.',
			),
		),
		array(
			'title'   => 'Nutrition in Cancer Prevention and Management',
			'excerpt' => 'How nutrient-rich, anti-inflammatory foods support cancer prevention and management, and which foods to limit.',
			'source'  => 'https://www.dietitianmichelle.com/nutrition-in-cancer-prevention-and-management/',
			'image'   => 'cancer-nutrition.webp',
			'body'    => array(
				'They say "you are what you eat" — but did you know your diet can also help prevent and manage cancer? What you put on your plate can either protect your body or increase your risk of developing cancer. Here are a few tips for nutrition in cancer prevention and management.',
				'<h2>Foods That Help</h2>',
				'The concept involves the intake of nutrient-rich, anti-inflammatory foods that help the body fight off abnormal cell growth. Some power-packed Nigerian foods include:',
				'<ul><li>Leafy greens — ugwu, efo, bitter leaf (rich in antioxidants)</li><li>Tomatoes — good for the prostate</li><li>Legumes — beans, lentils, moi-moi (plant protein fights inflammation)</li><li>Fatty fish — titus, mackerel, sardines (loaded with omega-3s)</li><li>Turmeric &amp; ginger — spices with anti-cancer properties</li></ul>',
				'<h2>Foods to Avoid or Reduce</h2>',
				'Some foods can increase inflammation and oxidative stress, making it easier for cancer cells to thrive. Reduce or avoid:',
				'<ul><li>Processed meats — suya, sausages, hot dogs (linked to colorectal cancer)</li><li>Fried and fast foods — puff-puff, akara, chips (trans fats trigger inflammation)</li><li>Sugary drinks and refined carbs — soft drinks, white bread, pastries</li><li>Alcohol and highly processed foods — the less, the better</li></ul>',
				'<h2>Take Charge of Your Health</h2>',
				'A healthy diet may not cure cancer, but it can make a big difference in its prevention and management. Your health is in your hands — eat well, live well.',
			),
		),
		array(
			'title'   => 'Classes of Food in Nigerian Dishes',
			'excerpt' => 'The seven essential nutritional classes found in Nigerian cuisine and how each contributes to health and flavor.',
			'source'  => 'https://www.dietitianmichelle.com/classes-of-food/',
			'image'   => 'classes-of-food.jpg',
			'body'    => array(
				'Today, we\'re taking a journey through the heart and soul of Nigerian cuisine — uncovering the seven essential classes of food that make our dishes truly extraordinary.',
				'<h2>1. Carbohydrates — The Energy Powerhouse</h2>',
				'Carbohydrates are the undisputed champions of energy — the fuel that keeps our bodies running. From pounded yam and fluffy eba to a hot plate of jollof rice, carbohydrates are the heart of Nigerian comfort food.',
				'<h2>2. Proteins — The Muscle Builders</h2>',
				'Picture a sizzling plate of spicy suya or a rich bowl of egusi soup brimming with meat and fish. These protein-packed dishes help us build and repair our bodies.',
				'<h2>3. Fats — The Flavour Enhancers</h2>',
				'Fats are the unsung heroes of flavour — from the golden sheen of palm oil in ogbono soup to the rich texture of a ripe avocado, fats add depth to our favourite dishes.',
				'<h2>4. Vitamins — The Immunity Boosters</h2>',
				'A vibrant plate of ugu leaves, pumpkin, and bitter leaf is bursting with essential vitamins and nutrients that keep us healthy and thriving.',
				'<h2>5. Minerals — The Foundation Builders</h2>',
				'From the calcium-rich goodness of bone-in fish like tilapia to the iron-packed punch of dark leafy greens like spinach, minerals are the building blocks of a healthy Nigerian diet.',
				'<h2>6. Fibre — The Digestive Champions</h2>',
				'A hearty bowl of beans or a plate of nutritious brown rice, packed with fibre, keeps our digestive systems happy and our bodies feeling satisfied.',
				'<h2>7. Water — The Elixir of Life</h2>',
				'While it may not technically be a "food," water is essential for our survival — whether it\'s coconut water or a tall glass of chilled kunu, staying hydrated is key to feeling our best.',
				'From the energy-boosting power of carbohydrates to the immunity-boosting properties of vitamins, Nigerian food truly has it all — so grab your fork and dig in!',
			),
		),
		array(
			'title'   => 'Lactose and Diet',
			'excerpt' => 'Understanding lactose intolerance and practical strategies for managing dairy — lactose-free alternatives and gradual reintroduction.',
			'source'  => 'https://www.dietitianmichelle.com/lactose-and-diet/',
			'image'   => 'lactose.jpg',
			'body'    => array(
				'When it comes to dairy products, understanding their impact on your diet and health is essential. Lactose, the sugar found in milk and dairy, plays a significant role in providing energy and nutrients — but for individuals with lactose intolerance, the inability to digest it can lead to digestive discomfort. This condition arises from insufficient levels of the enzyme lactase, needed to break down the sugar.',
				'<h2>Navigating Dairy Choices</h2>',
				'Despite lactose intolerance, many dairy options are available without discomfort. Opting for lactose-free or low-lactose alternatives, such as yogurt and cheese, can provide essential nutrients, and incorporating dairy into meals with other foods can help slow digestion and minimize discomfort.',
				'<h2>Practical Tips for Lactose Intolerance</h2>',
				'Gradually introducing dairy into the diet, choosing fermented options like yogurt and kefir, and taking lactase enzyme supplements can help improve tolerance over time. Listen to your body and identify individual triggers that may worsen symptoms.',
				'<h2>FAQs</h2>',
				'<strong>Is lactose intolerance the same as a dairy allergy?</strong> No — it\'s a digestive condition from a lactase enzyme deficiency, while a dairy allergy is an immune reaction to dairy proteins.',
				'<strong>Can I still get enough calcium without dairy?</strong> Yes — calcium-rich non-dairy sources include leafy greens, fortified plant-based milks, tofu, almonds, sardines, and finger millet.',
				'<strong>Are there lactose-free alternatives for all dairy products?</strong> Yes — milk, cheese, yogurt, and ice cream are all available in lactose-free or low-lactose versions.',
				'<h2>Conclusion</h2>',
				'Dairy can provide essential nutrients for overall health, but it\'s important to consider individual tolerance and preferences, and to consult a registered dietitian for personalized guidance.',
			),
		),
		array(
			'title'   => 'Sodium: Understanding its Sources and Health Impacts',
			'excerpt' => 'Sodium\'s essential role in the body, the risks of excess intake, and practical ways to reduce dietary salt.',
			'source'  => 'https://www.dietitianmichelle.com/sodium-understanding-its-sources-and-health-impacts/',
			'image'   => 'sodium.jpg',
			'body'    => array(
				'Sodium, a vital mineral, is a key component of table salt (sodium chloride), essential for bodily functions like fluid balance, nerve transmission, and muscle function. Despite its necessity, excessive intake — commonly through salt — can lead to health issues like hypertension and cardiovascular disease.',
				'<h2>Dietary Sources of Sodium</h2>',
				'<ol><li><strong>Table salt:</strong> the primary source of dietary sodium, often added during cooking or at the table.</li><li><strong>Processed foods:</strong> packaged and processed foods contain significant amounts, contributing to excessive intake.</li><li><strong>Natural sources:</strong> sodium also occurs naturally in many foods such as milk, beets, and celery.</li></ol>',
				'If you\'re accustomed to a diet heavy in salt, here are tips to help reduce your intake.',
				'<h2>Assess Your Sodium Intake</h2>',
				'If your typical meals include dishes like yam and egg sauce for breakfast, moi-moi for lunch, and eba with soup for dinner, you\'re likely consuming more salt than you realize — try quantifying it; it may exceed a teaspoon, the recommended daily limit.',
				'<h2>Simple Tips for Reducing Consumption</h2>',
				'<ol><li>Limit cooked food to no more than twice a day.</li><li>Avoid salty foods and refrain from adding salt to plain dishes like white rice, yam, or pasta.</li><li>Check food labels and steer clear of products high in sodium (more than 500mg per serving).</li><li>Soak salted items like stockfish before cooking to reduce sodium content.</li><li>Estimate the salt needed per serving, aiming for a maximum of 2.5 teaspoons for five servings.</li><li>Enhance flavour with pepper or natural spices like iru, ogiri, or dawadawa instead of adding table salt.</li><li>Be cautious of spice blends with excessive additives; opt for simpler, natural options.</li><li>When dining out, try to minimize the frequency for better control over your salt intake.</li></ol>',
			),
		),
		array(
			'title'   => 'Calorie! Calorie!! Calorie!!!',
			'excerpt' => 'What calories are, where they come from, and how they factor into weight loss and weight gain.',
			'source'  => 'https://www.dietitianmichelle.com/calorie-content-and-health/',
			'image'   => 'calorie.jpg',
			'body'    => array(
				'In today\'s health-conscious world, the term "calorie" is ubiquitous in discussions about diet, nutrition, and weight management. But what exactly is a calorie, and why does it matter?',
				'<h2>What is a Calorie?</h2>',
				'At its core, a calorie is a unit of measurement representing energy — specifically, the amount of energy required to raise the temperature of one gram of water by one degree Celsius. In nutrition, calories refer to the energy content of food and beverages.',
				'<h3>Measurement</h3>',
				'Calories are typically measured in kilocalories (kcal), often just called "calories" in everyday conversation. One kilocalorie equals 1,000 calories.',
				'<h3>Types</h3>',
				'Not all calories are created equal. Calories come from three primary macronutrients, each providing a different amount of energy per gram: carbohydrates (4 cal/g), proteins (4 cal/g), and fats (9 cal/g).',
				'<h3>In Food</h3>',
				'Food contains varying amounts of calories depending on its composition — for example, an avocado (150g) is about 240kcal, a chicken breast (85g) about 140kcal, a cup of cooked brown rice about 215kcal, a medium apple about 95kcal, and an ounce of almonds about 160kcal.',
				'<h3>Weight Loss and Weight Gain</h3>',
				'To lose weight, you need to consume fewer calories than your body expends, creating a deficit that forces your body to tap into stored fat. Conversely, consuming more calories than your body needs leads to weight gain, as the excess energy is stored as fat.',
				'<h3>Calorie Burning</h3>',
				'Your body burns calories constantly, even at rest, to sustain vital functions — this basal metabolic rate (BMR) accounts for most calories burned each day, with physical activity and exercise adding to that expenditure.',
				'<h3>Healthy Intake</h3>',
				'Individual calorie needs vary by age, gender, weight, and activity level. Aim to consume a balanced diet rich in whole foods, pay attention to portion sizes, and consult a healthcare professional or registered dietitian to determine your specific calorie needs.',
				'<h3>Empty Calories</h3>',
				'Some foods provide calories but lack significant nutritional value — "empty calories" like sugary beverages, sweets, and fried foods, which can contribute to weight gain and chronic disease risk if overconsumed.',
				'<h2>Conclusion</h2>',
				'Calories play a vital role in our daily lives as the currency of energy in the body. By understanding calorie balance and making informed choices about nutrition and exercise, we can achieve and maintain a healthy weight while supporting overall well-being.',
			),
		),
		array(
			'title'   => 'Detox Teas and Drinks: Myths and the Magic',
			'excerpt' => 'Debunking quick-fix detox tea claims — genuine wellness comes from sustainable habits, not miracle beverages.',
			'source'  => 'https://www.dietitianmichelle.com/detox-tea-and-drinks/',
			'image'   => 'detox-tea.jpg',
			'body'    => array(
				'Picture this: you\'re scrolling through social media, bombarded with images of flat tummies and glowing skin, all attributed to the latest "miracle" detox tea. Before you jump on the bandwagon, let\'s separate fact from fiction.',
				'<h2>The Rise of the Trend</h2>',
				'Detoxification has become a buzzword promising a quick fix to cleanse our bodies. But the truth is, our bodies are already equipped with amazing detoxification systems — the liver, kidneys, lungs, and skin work tirelessly to eliminate toxins and waste. So do we really need a fancy tea to do the job?',
				'<h2>Unveiling the Myths</h2>',
				'The allure of detox teas lies in their quick-fix promises, but the reality is less rosy:',
				'<ul><li><strong>Rapid weight loss:</strong> most rely on diuretics or laxatives, leading to temporary water weight loss, not actual fat burning — this can be dehydrating and unsustainable, even dangerous for some.</li><li><strong>False promises:</strong> claims often lack scientific backing and rarely address root causes.</li><li><strong>Potential side effects:</strong> some ingredients, like high doses of caffeine or unregulated herbs, can cause insomnia, stomach upset, or even liver damage.</li></ul>',
				'<h2>Genuine Benefits of Certain Ingredients</h2>',
				'While there\'s no "magic bullet," certain ingredients can offer real benefits when used responsibly: herbs and spices like ginger, dandelion root, and turmeric can aid digestion and support liver function; water is the ultimate detoxifier; and antioxidants from green tea, berries, and citrus fruits combat free radicals.',
				'<h2>Beyond the Teacup</h2>',
				'True detoxification goes beyond drinks — nourish your body with whole foods, exercise regularly, prioritize quality sleep, manage stress, and build sustainable habits that support your body\'s natural detoxification processes.',
				'<h2>Conclusion</h2>',
				'True detoxification is an ongoing process, not a quick fix. By prioritizing a balanced diet, regular exercise, quality sleep, and stress management, you can support your body\'s natural systems and achieve lasting well-being.',
			),
		),
		array(
			'title'   => 'How to Break Your Fast: A Guide to Healthy Fasting Endings',
			'excerpt' => 'A step-by-step approach to safely ending a fast — starting with hydration and easily digestible foods.',
			'source'  => 'https://www.dietitianmichelle.com/how-to-break-your-fast-a-guide-to-healthy-fasting-endings/',
			'image'   => 'break-fast.jpeg',
			'body'    => array(
				'People embark on fasting for a variety of reasons — religious practice, health benefits, weight loss, or simply personal choice. Whatever the reason, it\'s crucial to pay attention to how you break your fast, ensuring a smooth transition back to regular eating.',
				'<h2>Step-by-Step Guide to Breaking Your Fast</h2>',
				'<ol><li><strong>Start with hydration:</strong> if you\'ve been on a dry fast, kickstart your eating routine by drinking water — hydration prepares your digestive system for food.</li><li><strong>Opt for light, nutrient-rich foods:</strong> choose easily digestible options rich in carbohydrates and protein with minimal fat — fruit juice, fruit salad, yoghurt, parfait, kunu, or a cup of chocolate tea help restore energy and normal bodily function.</li><li><strong>Allow time before a full meal:</strong> wait 15 to 30 minutes before a more substantial, balanced meal, giving your digestive system a chance to adjust and avoiding discomfort, headaches, or dizziness.</li><li><strong>Stay hydrated:</strong> drink 1.5 to 2 litres of water, especially after a dry fast, to rehydrate effectively.</li><li><strong>Give time for digestion:</strong> stay active for at least 2 hours after your meal to aid proper digestion and prevent heartburn or indigestion.</li><li><strong>Healthy snacking:</strong> if you feel like snacking, opt for plantain chips, sweet potato chips, kuli-kuli, or popcorn.</li></ol>',
				'Avoid consuming a full breakfast, lunch, and dinner immediately after breaking your fast. Prepare a light breaking meal, followed by a balanced meal, a snack, and ample water — this prevents an overwhelming release of calories and glucose into your system, ensuring a smooth and beneficial fasting experience.',
			),
		),
		array(
			'title'   => 'Understanding Carbohydrates: A Guide for Management of Diabetes',
			'excerpt' => 'How carbohydrates affect blood sugar in diabetes, which food groups contain them, and portioning guidance.',
			'source'  => 'https://www.dietitianmichelle.com/carbohydrate-is-the-primary-nutrient-of-concern-in-diabetes-management/',
			'image'   => 'carbs-diabetes.jpg',
			'body'    => array(
				'For individuals managing diabetes, knowing the carbohydrate content in the foods they consume is crucial for maintaining healthy blood sugar levels — carbohydrates directly impact glucose levels, so tracking intake is essential for better control.',
				'<h2>The Six Essential Food Groups</h2>',
				'Nutrients come from six primary food groups: grains, cereals, roots and tubers; milk and dairy products; fish and meat; vegetables; fruits; and fats and oils. Of these, four are key sources of carbohydrates: grains/cereals/roots/tubers, milk and dairy, vegetables, and fruits.',
				'<h2>Why Worry About Carbohydrates and Diabetes</h2>',
				'Carbohydrates raise blood glucose levels, so limiting intake helps manage diabetes effectively. Many people assume vegetables don\'t contain carbs, but they do — just in much smaller quantities than grains or tubers.',
				'<h2>How Much Carbohydrate is in Vegetables?</h2>',
				'A cooked-vegetable serving (½ cup) or raw-vegetable serving (1 cup) contains about 5 grams of carbohydrates. Aim for 2–4 servings of vegetables daily, totaling 10–20 grams of carbohydrates — and remember, vegetables aren\'t just leafy greens; carrots, tomatoes, celery, cabbage, broccoli, spinach, and garden eggs all count.',
				'<h2>The Role of Exercise</h2>',
				'A balanced diet paired with regular exercise is central to managing diabetes and overall health. If you\'re just starting out, begin slowly to avoid injury — aim for around 5,000 steps (about 45 minutes) a day, and gradually work up to 10,000 steps as your stamina improves.',
				'<h2>Final Thoughts</h2>',
				'Managing diabetes and maintaining a healthy weight doesn\'t have to be difficult. By understanding which foods are sources of carbohydrates and incorporating regular physical activity, you can take control of your health — and it\'s always worth consulting a healthcare professional or dietitian for personalized advice.',
			),
		),
		array(
			'title'   => 'Boost Your Gut Health with Probiotics and Prebiotics',
			'excerpt' => 'How probiotics and prebiotics work together to support gut health, immunity, and brain function.',
			'source'  => 'https://www.dietitianmichelle.com/probiotics-and-prebiotics-for-gut-health/',
			'image'   => 'probiotics.jpg',
			'body'    => array(
				'Are you looking to improve your overall health and wellbeing? It all starts with a balanced gut. Having a healthy gut is crucial for preventing inflammation, boosting immunity, and supporting brain health.',
				'<h2>What Are Probiotics and Prebiotics?</h2>',
				'Probiotics are healthy, live microorganisms that reside in our gut, while prebiotics are the foods that feed these beneficial bacteria — think of probiotics as the good guys and prebiotics as their favourite snacks.',
				'<h3>Benefits</h3>',
				'A healthy gut prevents inflammation, supports immunity, and promotes brain health.',
				'<h3>Food Sources of Probiotics</h3>',
				'<ul><li>Kunu (sprouted rice)</li><li>Yogurt</li><li>Cheese</li><li>Sauerkraut (fermented cabbage)</li><li>Pickles (fermented cucumber)</li></ul>',
				'<h3>Food Sources of Prebiotics</h3>',
				'<ul><li>Banana</li><li>Grains (acha, oat, guinea corn, millet)</li><li>Legumes (beans, soybean)</li><li>Nuts (almond, groundnut, walnut)</li></ul>',
				'<h3>Fantastic Combos</h3>',
				'Try acha pudding with yogurt, banana, and nuts — or kunu with kuli-kuli (roasted peanut snack) — to boost your probiotic and prebiotic intake together.',
				'<h3>When to Take Probiotics as Treatment</h3>',
				'After taking antibiotics, it\'s essential to replenish your healthy gut microorganisms — probiotics can help restore that balance.',
				'<h2>Conclusion</h2>',
				'Ready to transform your gut health? A registered dietitian can help create a personalized plan, and simple recipe swaps can make gut-friendly eating easy and delicious.',
			),
		),
		array(
			'title'   => 'The Sweet Truth: Sugar and Sugar Substitutes',
			'excerpt' => 'Nigeria\'s high sugar consumption and its health risks, plus natural and artificial sweetener alternatives.',
			'source'  => 'https://www.dietitianmichelle.com/sugar-and-sugar-substitutes/',
			'image'   => 'sugar.jpg',
			'body'    => array(
				'Sugar is deeply woven into the fabric of Nigerian cuisine, from sweetened drinks to popular snacks like puff-puff and chin-chin. However, this indulgence comes at a cost — excessive sugar consumption has been linked to rising rates of obesity and diabetes, both becoming more prevalent across Nigeria.',
				'<h2>The Sugar Epidemic in Nigeria</h2>',
				'Nigeria is one of Africa\'s largest sugar consumers, and our sweet tooth has contributed to a sharp increase in non-communicable diseases such as diabetes, with obesity particularly concerning in urban areas where sugary snacks and drinks are more readily available.',
				'<h2>Natural Sugar Substitutes</h2>',
				'<ol><li><strong>Honey:</strong> packed with antioxidants and antibacterial properties.</li><li><strong>Coconut sugar:</strong> known for its low glycemic index and fewer calories than traditional sugar.</li><li><strong>Date palm sugar:</strong> a nutrient-rich sweetener derived from date palm sap.</li></ol>',
				'<h2>Artificial Sugar Substitutes</h2>',
				'<ol><li><strong>Aspartame:</strong> widely used in diet sodas and sugar-free products, though linked to headaches and digestive problems for some.</li><li><strong>Sucralose:</strong> a common sugar substitute that may negatively impact gut health.</li></ol>',
				'<h2>Healthier Alternatives</h2>',
				'<ol><li><strong>Stevia:</strong> a natural sweetener 200–300 times sweeter than sugar with virtually no calories.</li><li><strong>Yacon syrup:</strong> extracted from the yacon plant, rich in prebiotic fibres that promote gut health.</li></ol>',
				'<h2>Conclusion</h2>',
				'While sugar substitutes can help reduce overall sugar intake, it\'s crucial to prioritize natural options and use them in moderation. A healthier Nigeria requires a balanced diet rich in whole foods and mindful eating habits.',
			),
		),
		array(
			'title'   => 'Potassium Diet & Importance',
			'excerpt' => 'Potassium\'s role in fluid balance, nerve function, and heart rhythm, and how to reach the recommended daily intake.',
			'source'  => 'https://www.dietitianmichelle.com/essential-diet-the-power-of-potassium/',
			'image'   => 'potassium.jpeg',
			'body'    => array(
				'As dietitians, we often emphasize the importance of various nutrients in our diets, and one mineral that deserves special attention is potassium. Despite its crucial role in maintaining overall health, many people overlook its significance.',
				'<h2>Understanding Potassium</h2>',
				'Potassium is an electrolyte and mineral that plays a crucial role in maintaining fluid balance, nerve function, muscle contractions, and heart rhythm. It\'s often associated with bananas, but there are plenty of other potassium-rich foods to incorporate into your diet.',
				'<h2>The Benefits of Potassium</h2>',
				'<ol><li><strong>Blood pressure regulation:</strong> helps counteract the effects of sodium, promoting lower blood pressure and a reduced risk of hypertension and stroke.</li><li><strong>Heart health:</strong> supports heart function by regulating heartbeat and muscle contractions, and helps maintain normal cholesterol levels.</li><li><strong>Bone health:</strong> may help preserve bone mineral density, lowering the risk of osteoporosis and fractures, especially in older adults.</li><li><strong>Muscle function:</strong> essential for proper muscle function, helping prevent cramps and spasms, particularly after intense exercise.</li></ol>',
				'<h2>How Much Do You Need?</h2>',
				'The recommended daily intake for adults is around 3,500–4,700 milligrams, though many fall short due to poor dietary choices.',
				'<h3>Potassium-Rich Sources</h3>',
				'<ol><li><strong>Fruits:</strong> oranges, apricots, kiwi, cantaloupe, and bananas.</li><li><strong>Vegetables:</strong> leafy greens like spinach and kale, potatoes, sweet potatoes, and avocados.</li><li><strong>Legumes:</strong> beans, lentils, and peas.</li><li><strong>Dairy and fish:</strong> milk, yogurt, and fish like salmon and tuna.</li></ol>',
				'<h2>Tips for Increasing Potassium Intake</h2>',
				'<ol><li>Diversify your diet with a variety of potassium-rich foods across meals and snacks.</li><li>Steam or boil vegetables to retain potassium — frying can lead to some loss.</li><li>Read labels and choose products with higher potassium content, especially packaged or processed foods.</li><li>Balance potassium and sodium intake for healthy blood pressure levels.</li></ol>',
			),
		),
		array(
			'title'   => 'Child Nutrition For School Kids',
			'excerpt' => 'Why proper nutrition matters for children\'s cognitive function and physical development at school.',
			'source'  => 'https://www.dietitianmichelle.com/child-nutrition/',
			'image'   => 'child-nutrition.jpeg',
			'body'    => array(
				'As the back-to-school season starts, parents and caregivers want to ensure their kids have the energy, concentration, and nutrition needed for a great school year. The food choices we provide our children are highly instrumental in cognitive function, physical development, and overall health.',
				'<h2>Why Child Nutrition Matters</h2>',
				'Children\'s bodies are constantly changing and growing, and brain development calls for optimal nutrition. Omega-3 fatty acids, iron, and vitamins enhance memory and concentration, and balanced meals ensure stable energy levels for both physical activity and learning throughout the day.',
				'<strong>Immune system support:</strong> vitamins A and C, zinc, and other nutrients help keep the immune system strong, minimizing sick days.',
				'<strong>Healthy growth and development:</strong> key nutrients such as calcium, protein, and iron support proper bone and muscle development.',
				'<h2>Easy Tips for Parents and Caregivers</h2>',
				'<ol><li>Pack convenient, ready-to-eat, nutritious meals for lunchboxes so busy mornings don\'t mean skipping balanced food.</li><li>Encourage self-regulation — teach your child to listen to their own hunger and fullness cues.</li><li>Model healthy eating — children adopt good habits when they see caregivers eating well too.</li><li>Get professional advice from a dietitian, since each child\'s nutritional needs are unique.</li></ol>',
				'<h2>Put Your Child on the Road to Success</h2>',
				'Nutrition plays an important part in ensuring your child excels academically and stays healthy throughout the school year — a little planning in the kitchen goes a long way.',
			),
		),
		array(
			'title'   => 'Christmas Meals: How to Enjoy Without Overindulging — Nigerian Style!',
			'excerpt' => 'Mindful strategies for the festive season — portion control, pacing meals, and healthier beverage choices.',
			'source'  => 'https://www.dietitianmichelle.com/christmas-meals-how-to-enjoy-without-overindulging/',
			'image'   => 'christmas-meals.jpg',
			'body'    => array(
				'It\'s that time of year again — the season of love, jollof, and endless servings of small chops! Christmas in Nigeria is a real vibe. But overindulging during the festive season can come back to haunt us in January, both on the scale and in the wallet. So how do you enjoy Christmas meals without overdoing it? Let\'s break it down.',
				'<h2>Step 1: Master Portion Control</h2>',
				'Portion control doesn\'t mean starving yourself — it means eating mindfully and savouring every bite. Some guidelines for popular Nigerian dishes:',
				'<ul><li><strong>Jollof rice:</strong> about 1 cup, roughly the size of your fist.</li><li><strong>Chicken:</strong> one piece, grilled or roasted, is plenty.</li><li><strong>Moi-moi:</strong> half a wrap is fine.</li><li><strong>Salad:</strong> load up on vegetables, just go easy on creamy dressings — try a light vinaigrette instead.</li><li><strong>Snacks (chin-chin, puff-puff, etc.):</strong> a small handful, not a full meal.</li></ul>',
				'<h2>Step 2: Don\'t Skip Meals Before the Feast</h2>',
				'Skipping breakfast to "create space" for the Christmas buffet backfires — it causes you to overeat later. Start your day with a healthy breakfast, like oatmeal or boiled eggs with veggies, to stay satisfied.',
				'<h2>Step 3: Pace Yourself</h2>',
				'Christmas is a marathon, not a race. Take your time, savour the flavours, and join in conversations rather than rushing to seconds or thirds you don\'t really need.',
				'<h2>Step 4: Choose Drinks Wisely</h2>',
				'Sugary sodas and alcohol pack a punch in calories. Opt for healthier options like zobo, fresh fruit juice, tiger-nut milk, or plain water — and if you\'re having palm wine, remember moderation is key.',
				'<h2>Step 5: Use Smaller Plates</h2>',
				'Serving food on a smaller plate tricks your brain into feeling satisfied with less, reducing the temptation to pile your plate too high.',
				'<h2>Step 6: Don\'t Forget Dessert</h2>',
				'You can still have dessert — just keep it light. A small slice of fruitcake or a handful of fresh fruit is enough to satisfy your sweet tooth.',
				'<h2>Final Thoughts</h2>',
				'Christmas is about joy, togetherness, and celebrating life — food is part of it, but moderation is the magic word. Enjoy the food, savour the moments, and head into January feeling good. Merry Christmas!',
			),
		),
	);

	foreach ( $blog_posts as $post ) {
		$post_id = wp_insert_post(
			array(
				'post_type'    => 'post',
				'post_title'   => $post['title'],
				'post_content' => cnc_core_blockify_body( $post['body'] ),
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
