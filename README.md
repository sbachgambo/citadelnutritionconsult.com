# CNC Website Rebuild — Build Notes

Scaffolding for the rebuild described in [cnc-website-rebuild-brief.md](cnc-website-rebuild-brief.md). This is the code deliverable (#1–#2 in brief §8); content, brand assets, and commerce plugin configuration still need to happen in WP admin per §9.

## Design preview (`preview/`)

Before converting to the WP theme, `preview/` is a static, clickable HTML/CSS mockup of the full site — open `preview/index.html` in a browser and click through Home, Services, About Michelle, Shop, Learn, and Book. It shares the same color/type tokens as `theme.json` so nothing is re-decided when it's ported into the theme.

**Content is pulled from the live source sites** (cnutriconsult.com, citadelnutritionconsult.com, cncsmartfood.com, dietitianmichelle.com, and the dietitianmichelle.selar.com storefront) as of 2026-08-25 — real service names, consultation pricing tiers, FAQ, testimonials, product names/prices, and digital-product pricing, not invented placeholders. Still verify all of it against the live sites before launch: prices change, and some fields (a few Shop products, the Selar per-product checkout links) weren't fully available at fetch time and are marked "TBC" or generic in the markup.

**Real photography is now wired in** from `preview/images/` (client-supplied, sorted into `cnc/`, `michelle/`, `smartfoods/`, `ebooks/`): the CNC logo + favicon in every header, clinic/office/consultation photos, Michelle's headshots, all e-book covers (with real titles — several didn't match my earlier placeholder product names, so the Learn page lineup was rebuilt around what actually exists, including the "28-Day Challenge" box set and the discounted "Eat and Don't Eat" e-book at ₦2,000), and real product package renders on the Shop page (which also turned up two SKUs — Zobo Tea vs. Zobo Drink — that I'd previously merged into one). Not yet supplied: blog post images, and video for the "Diet Talk" embeds (currently reusing Michelle's photos as thumbnails).

**Color palette uses the official brand swatch.** Accent = olive green `#8FA84A` (confirmed brand hex), darkened to `#677935` for WCAG AA contrast (4.65:1) as both button and link text — the raw brand hex only hits 2.58:1 against the base background, which fails AA for text/UI. Light tint is a matching sage (`#E8ECDA`). Updated in both `preview/assets/css/style.css` and `wp-content/themes/cnc-theme/theme.json`, plus the placeholder-image URLs in the theme's patterns. The brand's orange (`#F0871E`) is not used as a UI color — kept to a single accent per the brief's "confident single accent color" direction; it remains in the logo mark itself.

**The preview has been ported into the actual WordPress theme.** Everything below reflects that — the theme is no longer just a skeleton.

## What's here

```
wp-content/
  themes/cnc-theme/       Block-based (FSE) theme — no hardcoded copy/colors
    theme.json             Color palette (official brand green), typography, spacing tokens
    templates/              front-page, page, single, archive, 404, page-services, page-shop, page-learn, page-book
    parts/                  header.html (site logo, full nav), footer.html (real contact info, nav, Diet Padi links)
    patterns/               hero-clinic, services-grid, services-full (new — pricing table), testimonials, about-michelle-teaser,
                             shop-teaser, learn-teaser, faq, booking-cta — all using real bundled photography, no placehold.co left
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
5. Site Editor → Pages: the homepage uses `front-page.html` automatically. Create the pages `/services/`, `/about-michelle/`, `/shop/`, `/learn/`, `/book/` and assign the matching custom template (Services Full Page / — / CNC Smartfoods Landing / Learn Landing / Book a Consultation) from each page's Template dropdown.
6. Install & activate **WooCommerce**. CNC Core detects it automatically (via an `admin_init` check, since activation order between the two plugins isn't guaranteed) and seeds the real CNC Smartfoods catalog the first time you load wp-admin afterward — 7 products published with real prices and package photography (Acha Flour, Acha Grains, Bean Flour, CNC Moringa/Cinnamon tea bags, CNC Zobo Drink, CNC Tigernut Drink), plus 4 more saved as **drafts** with no price (Finger Millet, Plantain Smartmix, Tom Brown, CNC Zobo tea bags) — these aren't sold individually on the live site yet, so they stay hidden from customers until you confirm pricing and publish them. Guarded by its own `cnc_core_woocommerce_seeded` option, so it also won't duplicate on reactivation.
7. Install the official **Paystack** gateway plugin (`woo-paystack` on WordPress.org, published by Paystack — [wordpress.org/plugins/woo-paystack](https://wordpress.org/plugins/woo-paystack/)). Then: WooCommerce → Settings → Payments → enable Paystack → add your Public and Secret keys (Paystack dashboard → Settings → API Keys & Webhooks). Use the **test** keys first and place a trial order before switching to live keys. Set WooCommerce → Settings → General currency to **Nigerian Naira (₦)** if it isn't already, since every seeded price is in Naira.
8. Spot-check the seeded Services/Testimonials/FAQ/Digital Products/Smartfoods screens and adjust copy, prices, or publish status as needed.

## Still pending (brief §9)

- **Brand palette/logo** — done; theme.json uses the confirmed official brand green (`#8FA84A`, darkened to `#677935` for text/button contrast).
- **Photography** — done for the default patterns (bundled real photos, no placehold.co left anywhere in the theme). Still pending: an About Michelle full-page template (video embeds, real-results section) mirroring `preview/about-michelle.html` — the generic `page.html` template covers it for now, built manually via blocks.
- **Copy** — real content is seeded via CNC Core (see above); still needs a final sign-off pass since it was pulled from live sites and Selar.
- **Blog** — 3 real posts (title, excerpt, and real featured image pulled from the live dietitianmichelle.com articles) are now seeded as standard Posts. Full article body text still needs migrating in — only the excerpt is seeded, with a content-editor comment linking back to the source URL as a reminder.
- **Diet Padi app store links** — now `https://dietpadi.com` everywhere in both the preview and the theme (header/footer/booking-cta). I searched for a public "Diet Padi" App Store / Google Play listing and found none under that name (only unrelated PADI diving apps and an unrelated calorie-counter app turned up) — either it isn't published under this name yet or isn't public; the client needs to supply the actual listing URLs.
- **Selar per-product links** — 6 of 9 seeded Digital Products now link to their real, individually-confirmed Selar checkout page (verified by fetching each one directly), with corrected real prices (e.g. Cancer Diet ₦5,000 was ₦10,000, Healing Recipe ₦8,000 was ₦20,000 — both different from my earlier guesses). The remaining 3 (28-Day Challenge bundle, Eat and Don't Eat, Weight Loss Diet Made Easy) weren't found under those exact names in the live storefront listing as of 2026-08-26 — still pointing at the general store page pending confirmation with Michelle.
- **Shop product prices** — confirmed the live cncsmartfood.com shop currently lists only 9 distinct products; Finger Millet, Plantain Smartmix, Tom Brown, and a separate Zobo tea-bag SKU (as opposed to the bottled Zobo Drink) aren't listed for individual online sale yet, so "TBC" pricing on those is accurate, not a research gap — confirm with the client whether they're in-store-only or not yet launched online.
- **WooCommerce** — code side is done: `seed-woocommerce.php` populates the real Smartfoods catalog (7 published, 4 draft pending price confirmation) the moment WooCommerce is active, with real package photography. Still needs: WooCommerce itself installed on a live site, the Paystack gateway plugin configured with real API keys (see setup step 7), and a live checkout test.
- **CNC Communities page** — added to the design preview (`preview/communities.html`, real content from citadelnutritionconsult.com/joinus/: 4 community groups, hospital/clinic partnership, careers) but not yet ported into the WP theme — no `page-communities.html` template/pattern exists yet. The WhatsApp, Paystack, and Google Forms links on that page are all placeholders pending the client's actual URLs.
- **Redirect map** — see `redirects-map.csv` below; a few rows still need a judgment call.

## Redirect map (retired domains → new site)

See [redirects-map.csv](redirects-map.csv) — built from each live site's actual navigation/page URLs (crawled 2026-08-25), not a guessed section-level mapping. A few rows are flagged "verify" where the new IA doesn't have an obvious 1:1 target (e.g. dietitianmichelle.com's `/podcast/`, `/affiliate-program/`). Import into Railway/host-level redirect rules (or a redirection plugin) before DNS cutover.
