# CNC Website Rebuild — Build Notes

Scaffolding for the rebuild described in [cnc-website-rebuild-brief.md](cnc-website-rebuild-brief.md). This is the code deliverable (#1–#2 in brief §8); content, brand assets, and commerce plugin configuration still need to happen in WP admin per §9.

## Design preview (`preview/`)

Before converting to the WP theme, `preview/` is a static, clickable HTML/CSS mockup of the full site — open `preview/index.html` in a browser and click through Home, Services, About Michelle, Shop, Learn, and Book. It shares the same color/type tokens as `theme.json` so nothing is re-decided when it's ported into the theme.

**Content is pulled from the live source sites** (cnutriconsult.com, citadelnutritionconsult.com, cncsmartfood.com, dietitianmichelle.com, and the dietitianmichelle.selar.com storefront) as of 2026-08-25 — real service names, consultation pricing tiers, FAQ, testimonials, product names/prices, and digital-product pricing, not invented placeholders. Still verify all of it against the live sites before launch: prices change, and some fields (a few Shop products, the Selar per-product checkout links) weren't fully available at fetch time and are marked "TBC" or generic in the markup.

**Real photography is now wired in** from `preview/images/` (client-supplied, sorted into `cnc/`, `michelle/`, `smartfoods/`, `ebooks/`): the CNC logo + favicon in every header, clinic/office/consultation photos, Michelle's headshots, all e-book covers (with real titles — several didn't match my earlier placeholder product names, so the Learn page lineup was rebuilt around what actually exists, including the "28-Day Challenge" box set and the discounted "Eat and Don't Eat" e-book at ₦2,000), and real product package renders on the Shop page (which also turned up two SKUs — Zobo Tea vs. Zobo Drink — that I'd previously merged into one). Not yet supplied: blog post images, and video for the "Diet Talk" embeds (currently reusing Michelle's photos as thumbnails).

**Color palette uses the official brand swatch.** Accent = olive green `#8FA84A` (confirmed brand hex), darkened to `#677935` for WCAG AA contrast (4.65:1) as both button and link text — the raw brand hex only hits 2.58:1 against the base background, which fails AA for text/UI. Light tint is a matching sage (`#E8ECDA`). Updated in both `preview/assets/css/style.css` and `wp-content/themes/cnc-theme/theme.json`, plus the placeholder-image URLs in the theme's patterns. The brand's orange (`#F0871E`) is not used as a UI color — kept to a single accent per the brief's "confident single accent color" direction; it remains in the logo mark itself.

**The preview has been ported into the actual WordPress theme.** Everything below reflects that — the theme is no longer just a skeleton.

## Client review feedback round 1 (2026-08-28)

Client reviewed the live preview and sent 7 corrections, applied to both `preview/` and the theme:

1. Fixed a typo introduced by an earlier "personalized→customized" pass ("Customized guidance... customized meal planning" repetition).
2. One-on-One Consultations now says "a registered dietitian," not "Dietitian Michelle" specifically — it's not a solo service.
3. Removed the ₦20,000/₦7,000 Culinary Services pricing per client request (kept the description, dropped the price line).
4. **Pricing table rebuilt entirely from two real CNC flyers the client provided** — this supersedes the guessed/sourced pricing from the earlier live-site audit: Consultation ₦15,000, Customized Plan ₦30,000, Gold ₦60,000, Premium ₦180,000, **Citadel ₦380,000 (was ₦360,000)**, Diet Plan Only ₦15,000, **Follow Up ₦7,500 (was ₦7,000)**. The old standalone "Nutritional Assessment ₦3,000" and "Basic Plan" line items are gone — the flyer folds assessment into every plan and doesn't list them separately.
5. Added a "Where CNC Smartfoods came from" origin-story section to the Shop page, linking the clinic, Michelle, and the product line — marked as a **draft narrative pending the client's fact-check**, since I don't have the real founding story, only a plausible one grounded in what's documented elsewhere on the site.
6. Noted "Diet Talk with Dietitian Michelle" is a **radio show** (also available as video) — corrected the About Michelle page's framing, which previously only described it as a YouTube series.
7. Accountability program price corrected: ₦30,000 → ₦50,000 for 1 month.

Also added, from the flyers: a second phone number (+234 708 987 3497) alongside the existing one in every footer.

**Known gap this round didn't touch:** the About Michelle page's "Diet Talk" video section and radio-show mention only exist in `preview/` — there's still no full About Michelle template in the theme (flagged earlier under "Still pending"), so item 6 hasn't been ported to the theme yet.

**Real WordPress install test (2026-08-27).** Everything above was verified end-to-end on a real, throwaway local WordPress install (WP 7.1, SQLite backend via the official SQLite Database Integration plugin, PHP 8.3, no MySQL needed) — not just PHP-linted, actually run:
- CNC Core and CNC Theme both activate with zero fatal errors or warnings in `debug.log`.
- Content seeding verified by direct count: 9 Services, 4 Testimonials, 5 FAQ, 9 Digital Products, 3 blog Posts, 21 media attachments — all exactly as designed.
- All 7 pages (front page + Services, About Michelle, Shop, Learn, Book, Communities) return HTTP 200 with no PHP errors in the rendered HTML.
- The Services pricing table, the Learn page's per-product block bindings (price + Selar link resolving correctly from post meta), the Communities page, and the Diet Padi links all confirmed rendering with real content.
- WooCommerce installed and activated live; `seed-woocommerce.php` produced exactly 7 published + 4 draft products with real photography, and the draft products correctly stayed hidden from the public `/shop/` page. One benign WooCommerce-core notice appeared in the log (a known translation-loading timing quirk, unrelated to CNC code) — not a functional issue.

## What's here

```
wp-content/
  themes/cnc-theme/       Block-based (FSE) theme — no hardcoded copy/colors
    theme.json             Color palette (official brand green), typography, spacing tokens
    templates/              front-page, page, single, archive, 404, page-services, page-communities, page-blog, page-shop, page-learn, page-book
    parts/                  header.html (site logo, full nav), footer.html (real contact info, nav, Diet Padi links)
    patterns/               hero-clinic, services-grid, services-full (pricing table), communities-full (groups, partnerships, careers),
                             blog-full (3-col card grid), testimonials, about-michelle-teaser, shop-teaser, learn-teaser, faq,
                             booking-cta — all using real bundled photography, no placehold.co left
    assets/images/          brand/ (logo, favicon), icons/ (9 real 3D service icons), patterns/ (4 default hero/teaser photos)
    assets/css/interactions.css, assets/js/interactions.js   Hover-lift, scroll-reveal, and mouse-tilt motion layer ported from the preview
  plugins/cnc-core/        Custom post types: Service, Testimonial, FAQ Item, Digital Product
    includes/post-types.php
    includes/meta.php       cnc_price / cnc_selar_url meta on Digital Product, bound into the Learn page pattern via core block bindings (WP 6.5+)
    includes/seed-content.php       Seeds real starter content (Services, Testimonials, FAQ, Digital Products, 3 blog Posts) on activation
    includes/seed-woocommerce.php   Seeds the real CNC Smartfoods catalog once WooCommerce is detected active — see setup step 6 below
    seed-assets/             Service icons, e-book covers, blog images, Smartfoods package photos — sideloaded into the Media Library once, then left alone
```

Services, testimonials, and FAQ patterns pull live from their CPTs via query loops — adding/editing/reordering entries in wp-admin updates the homepage (and the new `/services/` page) automatically. Digital Products work the same way on `/learn/`, with price and the Selar checkout link editable per-post via the sidebar (bound to the button/price text through core block bindings).

**Content seeding:** activating CNC Core inserts the real content gathered from the live sites — 9 Services (with their real 3D icons as featured images), 4 Testimonials, 5 FAQ entries, and 9 Digital Products (with real e-book cover art, price, and a Selar link). This runs once (`cnc_core_seeded` option guards it) — it will never overwrite or duplicate on reactivation, and staff can freely edit or delete any of it afterward like any other post.

## Local setup

1. Stand up WordPress + MariaDB (Railway's WordPress template, or any local WP env for preview).
2. Copy `wp-content/themes/cnc-theme` and `wp-content/plugins/cnc-core` into the site's `wp-content/`.
3. Activate **CNC Core** (this seeds the starter content — give it a moment, it sideloads ~15 images), then activate the **CNC Theme**.
4. Appearance → Site Identity: upload `wp-content/themes/cnc-theme/assets/images/brand/cnc-logo.png` as the Custom Logo and `favlogo.png` as the Site Icon. The theme shows its own bundled versions as a fallback until you do, so nothing is blank in the meantime.
5. Site Editor → Pages: the homepage uses `front-page.html` automatically. Create the pages `/services/`, `/about-michelle/`, `/shop/`, `/learn/`, `/book/`, `/communities/`, `/blog/` and assign the matching custom template (Services Full Page / — / CNC Smartfoods Landing / Learn Landing / Book a Consultation / CNC Communities / Blog Listing) from each page's Template dropdown. Delete the default "Hello world!" post so it doesn't show on the Blog listing.
6. Install & activate **WooCommerce**. CNC Core detects it automatically (via an `admin_init` check, since activation order between the two plugins isn't guaranteed) and seeds the real CNC Smartfoods catalog the first time you load wp-admin afterward — 7 products published with real prices and package photography (Acha Flour, Acha Grains, Bean Flour, CNC Moringa/Cinnamon tea bags, CNC Zobo Drink, CNC Tigernut Drink), plus 4 more saved as **drafts** with no price (Finger Millet, Plantain Smartmix, Tom Brown, CNC Zobo tea bags) — these aren't sold individually on the live site yet, so they stay hidden from customers until you confirm pricing and publish them. Guarded by its own `cnc_core_woocommerce_seeded` option, so it also won't duplicate on reactivation.
7. Install the official **Paystack** gateway plugin (`woo-paystack` on WordPress.org, published by Paystack — [wordpress.org/plugins/woo-paystack](https://wordpress.org/plugins/woo-paystack/)). Then: WooCommerce → Settings → Payments → enable Paystack → add your Public and Secret keys (Paystack dashboard → Settings → API Keys & Webhooks). Use the **test** keys first and place a trial order before switching to live keys. Set WooCommerce → Settings → General currency to **Nigerian Naira (₦)** if it isn't already, since every seeded price is in Naira.
8. Spot-check the seeded Services/Testimonials/FAQ/Digital Products/Smartfoods screens and adjust copy, prices, or publish status as needed.

## Sitemap audit (2026-08-27) — citadelnutritionconsult.com & cnutriconsult.com

Went beyond the original nav-based crawl and pulled the actual XML sitemaps for citadelnutritionconsult.com and cnutriconsult.com to find pages that don't show up in a site's main menu. Real gaps found and closed immediately:

- **Careers** — the Communities page said "no open positions," but `/career/` has a real, current listing ("Virtual Dietitian, Remote") with a real Google Form application link. Fixed on both the preview and the theme.
- **More partnership tracks** — `/partner-with-us/` describes five tracks (Hospital, Gym, Chef, Dietitian, Affiliate); only Hospital was built. Added Gym and Chef Partnership cards to the Communities page/pattern.
- **CNC Refresh Brochure** — a free downloadable resource on Selar (`/1wk361`) that wasn't in the Learn lineup. Added as a 10th item (no cover art available yet — flagged for upload before launch).
- **Team roster** — `/dietitians-chefs/` lists 6 real dietitians and 8 real chefs across Nigeria, never represented anywhere on the new site. Added a "Meet the wider team" section to the preview and a matching `about-michelle-team.php` pattern in the theme (insert it into the About Michelle page via the block editor).
- **`/dietpadi-waitlist/`** — worth flagging directly: this page shows Diet Padi is still in a pre-launch "join the waitlist" phase with a capped signup counter, not a publicly downloadable app. Combined with finding no real App Store/Play Store listing earlier, the Book page's "download the app" framing may need to change to "join the waitlist" — a messaging decision for the client, not something I changed unilaterally.
- **`/reviews/` (cnutriconsult.com)** — checked directly: broken/abandoned template content (Lorem Ipsum, wrong industry, fake reviewer names). Confirmed nothing to migrate.
- Everything else found (duplicate/test pages, WooCommerce auto-generated cart/checkout pages, Paystack payment-confirmation callback pages, an archived old homepage) is transactional or superseded, not real content — mapped to sensible redirect targets in `redirects-map.csv` rather than built as pages.
- Also fixed a real bug in `redirects-map.csv` found during this pass: several note fields had unescaped commas that silently broke CSV column alignment (pre-dating this audit) — all rows now validated at exactly 5 fields.

## Sitemap audit (2026-08-27) — dietitianmichelle.com

Same treatment for the third domain. This one turned up the single biggest content gap in the whole project:

- **Blog — 30 real posts existed, only 3 were migrated.** The original discovery only found 3 posts via a nav-menu crawl; the actual `post-sitemap.xml` lists 30. All 27 remaining posts have now been fetched (real title, excerpt, and featured image for each) and seeded — see the "Blog — fully migrated" line above. Full article body text still needs migrating in for all 30; only excerpts are seeded so far.
- **Real pricing found for two existing services** — `/other-services/` lists Culinary Services at ₦20,000/recipe (staff training) or ₦7,000/meal (prepared meals), and a Follow-Up/Accountability program at ₦30,000 for 30 days. Neither price existed anywhere on the site before; added to both the Culinary Services and Group Coaching & Follow-Up service descriptions.
- **`/community/`** — checked live: it's the same "Dietitian Michelle Support Group" (WhatsApp, ₦1,000) already on the Communities page. Nothing new to add.
- **`/elementor-2864/`** — a duplicate rendering of the homepage under an Elementor-generated slug. No action beyond a redirect.
- **`/elementor-landing-page-1821/`** — a 2022-dated tutorial-booking page priced in USD ($90/lesson). Superseded by the current Naira-priced Culinary Services found on `/other-services/` — not migrated as current truth, redirected to `/services/` instead.

## Still pending (brief §9)

- **Brand palette/logo** — done; theme.json uses the confirmed official brand green (`#8FA84A`, darkened to `#677935` for text/button contrast).
- **Photography** — done for the default patterns (bundled real photos, no placehold.co left anywhere in the theme). Still pending: an About Michelle full-page template (video embeds, real-results section) mirroring `preview/about-michelle.html` — the generic `page.html` template covers it for now, built manually via blocks. The new `about-michelle-team.php` pattern (real dietitian/chef roster) is ready to insert into that page whenever it's built.
- **Copy** — real content is seeded via CNC Core (see above); still needs a final sign-off pass since it was pulled from live sites and Selar.
- **Blog — fully migrated.** All 30 real posts from dietitianmichelle.com's sitemap (not just the 3 found in the original nav crawl) are seeded as standard Posts with real title, excerpt, and featured image, alongside the `/blog/` listing page (`page-blog.html` template + `blog-full.php` pattern, 3-column card grid, paginated 9-per-page). Verified live end-to-end on a clean re-seed: exact expected counts (9 Services, 4 Testimonials, 5 FAQ, 10 Digital Products, 30 Posts), zero errors in `debug.log`, real titles/images rendering on both the listing and individual post pages. One thing to do before launch: delete WordPress's default "Hello world!" post, and migrate in the full article body text for each post — only the excerpt is seeded, with a content-editor comment linking back to the source URL as a reminder.
- **Diet Padi app store links** — now `https://dietpadi.com` everywhere in both the preview and the theme (header/footer/booking-cta). I searched for a public "Diet Padi" App Store / Google Play listing and found none under that name (only unrelated PADI diving apps and an unrelated calorie-counter app turned up) — either it isn't published under this name yet or isn't public; the client needs to supply the actual listing URLs.
- **Selar per-product links** — 6 of 9 seeded Digital Products now link to their real, individually-confirmed Selar checkout page (verified by fetching each one directly), with corrected real prices (e.g. Cancer Diet ₦5,000 was ₦10,000, Healing Recipe ₦8,000 was ₦20,000 — both different from my earlier guesses). The remaining 3 (28-Day Challenge bundle, Eat and Don't Eat, Weight Loss Diet Made Easy) weren't found under those exact names in the live storefront listing as of 2026-08-26 — still pointing at the general store page pending confirmation with Michelle.
- **Shop product prices** — confirmed the live cncsmartfood.com shop currently lists only 9 distinct products; Finger Millet, Plantain Smartmix, Tom Brown, and a separate Zobo tea-bag SKU (as opposed to the bottled Zobo Drink) aren't listed for individual online sale yet, so "TBC" pricing on those is accurate, not a research gap — confirm with the client whether they're in-store-only or not yet launched online.
- **WooCommerce** — verified working end-to-end on a real install (see above): `seed-woocommerce.php` populates the real Smartfoods catalog (7 published, 4 draft pending price confirmation) the moment WooCommerce is active, with real package photography, correctly rendering on `/shop/`. Still needs: WooCommerce on the *actual* production site, the Paystack gateway plugin configured with real API keys (see setup step 7), and a live checkout test with real money.
- **CNC Communities page** — done in both the design preview (`preview/communities.html`) and the WP theme (`page-communities.html` template + `communities-full.php` pattern, nav updated everywhere), with real content from citadelnutritionconsult.com/joinus/: 4 community groups, hospital/clinic partnership, careers. The WhatsApp, Paystack, and Google Forms links on that page are all placeholders (`href="#"`) pending the client's actual URLs.
- **Redirect map** — see `redirects-map.csv` below; fully resolved, no outstanding judgment calls.

## Redirect map (retired domains → new site)

See [redirects-map.csv](redirects-map.csv) — built from each live site's actual navigation/page URLs (crawled 2026-08-25), not a guessed section-level mapping. Every row has a resolved destination now (e.g. `/affiliate-program/` and `/joinus/` both now correctly point at the new `/communities/` page). Import into Railway/host-level redirect rules (or a redirection plugin) before DNS cutover.
