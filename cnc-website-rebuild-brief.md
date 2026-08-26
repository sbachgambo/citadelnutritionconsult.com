# Citadel Nutrition Consult (CNC) — Website Rebuild Brief

## 1. Project summary

Rebuild four existing web properties into a single WordPress website at `citadelnutritionconsult.com`, built as a **fully customizable, no-code-editable theme** so non-technical staff can update text, images, and sections after launch without touching code.

Source properties being merged/retired:

| Site | Role | Fate |
|---|---|---|
| citadelnutritionconsult.com | Clinic/institutional brand | Becomes the new unified site |
| cnutriconsult.com | Redesign of the above | Retired, 301 redirect to new site |
| cncsmartfood.com | Product/e-commerce arm (NAFDAC-listed food packs) | Content merged into `/shop`, domain 301 redirects |
| dietitianmichelle.com | Personal brand of Dietitian Michelle (blog, YouTube, e-book, courses) | Content merged into `/about-michelle` and `/learn`, domain 301 redirects |

**Not in scope:** the Diet Padi app (app.dietpadi.com). It stays exactly as-is on its own Railway service. The new site only links out to it for bookings — no code integration in this phase.

**Hosting target:** Railway, WordPress + MariaDB (Railway's WordPress template), persistent volume for `wp-content`.

## 2. Brand structure (visitor mental model)

CNC is one practice with three faces. The homepage leads with the clinic (institutional trust), then branches to the personal brand and commerce:

1. **Home / Services** — institutional CNC: consultations, hospital/gym/chef partnerships, MNT services. First impression = credible clinic.
2. **About Michelle** — her bio, credentials, blog content, YouTube embeds. The human face behind the clinic.
3. **Shop** — CNC Smart Foods physical products (therapeutic/treatment food packs). WooCommerce + Paystack.
4. **Learn (Courses & e-book)** — digital products. NOT sold through WooCommerce — each item links out to its existing Selar checkout page.
5. **Book a consultation** — explains the process, CTA to download the Diet Padi app (App Store / Play Store links). No embedded calendar in this phase.

## 3. Content strategy

- **Reuse, re-engineer, and rewrite** existing copy from all four source sites rather than starting blank. Consolidate duplicate content (citadelnutritionconsult.com + cnutriconsult.com say the same things — merge into one clean version).
- Carry over: testimonials (same set appears on both CNC and Michelle's site — use once, attribute naturally), FAQ, service descriptions, product descriptions (NAFDAC/health-condition framing for food packs), blog posts from dietitianmichelle.com, YouTube series references ("Diet Talk with Dietitian Michelle").
- Rewrite for one consistent voice/tone across what were three separately-written sites.

## 4. Commerce requirements

- **Physical products (Smart Foods):** WooCommerce, Paystack payment gateway, standard cart/checkout/shipping flow.
- **Digital products (courses, e-book):** Do NOT build a second cart. Each course/e-book is a content block (image, title, short description, price) with a "Buy now" button that links directly to its existing Selar product page. Selar handles checkout, delivery, and payment entirely off-site.
- Keep these two commerce paths visually distinct so customers aren't confused about where checkout happens.

## 5. Booking requirements

- Phase 1 (this build): a "Book a consultation" section/page describing how booking works, with prominent app download badges (iOS/Android) linking to Diet Padi. No calendar widget, no backend integration.
- Phase 2 (future, out of scope now): embedded booking calendar talking to Diet Padi's API directly. Leave a clean content area/placeholder that could later host this without a redesign.

## 6. Design direction

- **Style:** minimalist, professional, with 3D-rendered visual accents (icons/illustrations) rather than flat icons or generic stock photography. Generous whitespace, confident single accent color, clean typography.
- **Color palette:** pending — brand logos will be supplied later. Build the theme so the palette is defined via a small number of CSS custom properties / theme.json color slots (not hardcoded throughout), so swapping in the real logo-derived palette later is a config change, not a rebuild. Use a neutral base (soft white/gray) with a placeholder health-associated accent (e.g. deep teal/green) until real brand colors arrive.
- **Imagery:** placeholders until brand photography of Michelle/clinic/products is supplied.

## 7. Technical/editability requirements

This is the core requirement: **staff must be able to edit content without code afterward.**

- Build as a **block-based WordPress theme** (full site editing / `theme.json`) or a classic theme paired with a page builder (e.g. Advanced Custom Fields + Gutenberg blocks), so that:
  - Text, images, and testimonials on any page are editable via the WordPress editor.
  - New blog posts, FAQ entries, and product/course entries can be added via standard WP admin screens (custom post types recommended for: Services, Testimonials, FAQ, Courses/Digital Products).
  - Section order/visibility on the homepage is controllable via the editor, not hardcoded in template files.
  - Color/typography tokens are exposed as theme customizer or `theme.json` settings, not hardcoded in CSS.
- Standard WordPress/WooCommerce plugin stack — avoid custom PHP unless necessary for the digital-product Selar links or CPT registration.
- SEO: proper meta titles/descriptions per page, XML sitemap, and 301 redirect rules mapped from all retired domains' key URLs to their new equivalents (to preserve search rankings).
- Performance: image optimization/lazy loading, caching-friendly setup (Railway persistent volume for uploads).
- Deployment target: Railway (WordPress + MariaDB template), custom domain + SSL via Railway.

## 8. Deliverables for this build phase

1. WordPress theme (block-based or ACF-powered) matching the structure above.
2. Custom post types: Services, Testimonials, FAQ, Digital Products (course/e-book cards linking to Selar).
3. WooCommerce configured with Paystack for the Shop.
4. Rewritten/merged content populated into the above (from source material provided separately).
5. Redirect map from the three retired domains to the new site's URL structure.
6. Deployed and tested on Railway before DNS cutover.

## 9. Open items pending before/during build

- Brand assets: logo, final color palette, photography.
- Final copy sign-off (draft content to be provided separately from this brief).
- Confirmation of exact Diet Padi app store links for the booking CTA.
