# Guruswad Ocean — WordPress + WooCommerce Theme

An ultra-premium corporate and e-commerce theme for **Guruswad Ocean Private Limited** and its first brand, **MilletRush**.
It implements the *Website Blueprint v1.0*: quiet-luxury editorial design, scroll storytelling with GSAP, English + Marathi, a WooCommerce shop and a full B2B enquiry engine.

Built by **Imperial Tech Innovations** · *Engineer the Future*

---

## 1. Requirements

| Item | Version |
|---|---|
| WordPress | 6.4 or newer |
| PHP | 7.4 or newer (8.2+ recommended) |
| WooCommerce | 8.0 or newer |
| Server | HTTPS, PHP mail or an SMTP plugin, page caching (LiteSpeed / Redis) recommended |

## 2. Install (10 minutes)

1. **Appearance → Themes → Add New → Upload Theme** → choose `guruswad-ocean.zip` → **Activate**.
2. On activation the theme automatically:
   - creates the pages **Home, About Us, Manufacturing & Processing, Import & Export, Farmers & FPO Network, Investment & Partnership, Contact Us, News & Updates** with the right templates;
   - sets *Home* as the front page and *News & Updates* as the posts page;
   - creates the **MilletRush** brand entry;
   - sets pretty permalinks (`/%postname%/`).
3. Install and activate the recommended plugins (section 4).
4. **Appearance → Customize → Guruswad Ocean** — fill in the phone, WhatsApp number, email, address, social links, enquiry routing emails, catalogue PDF and (optional) hero video.
5. **Appearance → Menus** — optional. Until menus are built, the theme shows a complete default navigation.
6. Add products in **Products → Add New**. Mark 4 as *Featured* to control the home page product row.

## 3. What's inside

| Area | Where | Notes |
|---|---|---|
| Home (9 scroll "movements") | `front-page.php` | Hero, manifesto ink-fill, pinned Grain Journey, MilletRush feature, products, farmer counters, manufacturing, trade map, invest CTA |
| About Us | `page-templates/template-about.php` | Founder note, vision/mission, 7 business activities, leadership (from *Leadership* entries), roadmap |
| Manufacturing | `page-templates/template-manufacturing.php` | 6-step process, infrastructure, ICAR-IIMR panel, certifications (from *Certifications* entries) |
| Import & Export | `page-templates/template-export.php` | Catalogue, private label, import, animated trade map, **4-step RFQ** |
| Farmers & FPO | `page-templates/template-farmers.php` | Impact counters, Marathi quote, ecosystem cards, farmer stories (from *Farmer Stories*) |
| Investment | `page-templates/template-investment.php` | Thesis, partnership models, investor form |
| Contact / Enquiry Hub | `page-templates/template-contact.php` | One form, **7 enquiry types** (General, Product, Export/RFQ, Bulk, Import, Investor, Partnership) |
| Our Brands | `archive-gor_brand.php`, `single-gor_brand.php` | Brand World template — add a new brand in **Brands → Add New**, no code |
| Shop & product | `woocommerce.php`, `inc/woocommerce.php` | Category chips, B2B buttons on every product, WhatsApp, trust row, "From the field" strip, Nutrition & Specs tabs, slide-out cart, GSTIN at checkout |
| News / Journal | `index.php`, `single.php` | Editorial grid with reading time |
| Enquiry engine | `inc/enquiries.php` | Saves every enquiry in **Enquiries** (admin), routes email per type, auto-acknowledges sender, status (New / Contacted / Closed), **Download CSV**, honeypot + timing spam protection |
| Bilingual | `inc/i18n.php`, `languages/` | Polylang or WPML switcher, Marathi translation file, Devanagari typography rules |
| Design system | `assets/css/main.css` | Tokens (palette from the logo, type scale, spacing, motion), components |
| Motion | `assets/js/main.js` | GSAP + ScrollTrigger + Lenis, all bundled locally |

### Product fields (Products → Edit → General tab)
Millet type, sourcing region, pack sizes, shelf life, storage, HS code, export MOQ, ingredients, nutrition (one line per row: `Energy | 410 kcal`), export-ready flag. These feed the product tabs and the provenance strip.

## 4. Recommended plugins

| Purpose | Plugin | Licence |
|---|---|---|
| Shop | WooCommerce | Free |
| Marathi + English | **Polylang Pro + Polylang for WooCommerce** *or* **WPML + WooCommerce Multilingual** | Paid (annual, vendor) |
| Payments (UPI, cards, net-banking) | Razorpay for WooCommerce | Free plugin; per-transaction fee |
| Shipping & tracking | Shiprocket | Free plugin; per-shipment fee |
| GST invoices (HSN, CGST/SGST/IGST) | A GST invoice plugin of choice | Free / paid |
| SEO + schema | Rank Math or Yoast | Free / paid |
| Security | Wordfence (firewall, 2FA, login limits) | Free / paid |
| Backups | UpdraftPlus (daily, off-site) | Free / paid |
| Cache / CDN | LiteSpeed Cache or WP Rocket + Cloudflare | Free / paid |
| Email delivery | WP Mail SMTP (so enquiry emails are not marked spam) | Free |
| Spam (optional) | Cloudflare Turnstile | Free |
| Analytics | Site Kit by Google (GA4 + Search Console) | Free |

Premium licences are the client's own recurring third-party costs.

## 5. Marathi (मराठी)

1. Install Polylang (or WPML), add **English** and **मराठी (mr)**.
2. Translate pages, products and brands in the plugin's interface.
3. Theme text (buttons, headings, forms) is translated by `languages/mr.po / mr.mo`. **216 of 451 strings** — every visitor-facing string on the main pages — are pre-translated as a starting draft. **A native Marathi writer should review them** (blueprint §5.4). Edit with *Loco Translate* or Poedit; the template is `languages/guruswad.pot`.
4. Devanagari uses *Tiro Devanagari Marathi* (headings) and *Mukta* (body). Letter-spacing and uppercase are switched off automatically for Marathi so conjuncts never break.

## 6. Motion & accessibility

- All effects respect the visitor's **Reduce motion** setting: movement is removed and content shows immediately.
- If JavaScript fails or is slow, a 3.5-second safety switch shows all content.
- Smooth scrolling (Lenis) runs only on mouse/trackpad devices; phones keep native scrolling.
- Only `transform` and `opacity` are animated; the hero particle canvas pauses when off-screen.
- Target: WCAG 2.2 AA (contrast, visible focus, skip link, labelled forms, `inert` closed menu).

Signature effects: split-text headline reveal · manifesto ink-fill · pinned horizontal Grain Journey with a self-drawing swoosh · layered parallax · clip-path image reveals · counters · floating brand logo · animated export routes · magnetic buttons · custom cursor · curtain page transitions (native View Transitions where supported) · marquee · footer wordmark rise · grain burst on add-to-cart.

## 7. Performance

- **No third-party requests**: fonts (OFL) and GSAP/Lenis are self-hosted in `assets/fonts` and `assets/vendor`.
- Scripts are `defer`red; the display font is preloaded; emoji scripts removed.
- Hero video (optional) uses `preload="none"` with a poster; add a compressed MP4 (≤ 4 MB, 1080p, no audio).
- Upload images as WebP/AVIF; registered sizes: `gor-hero`, `gor-panel`, `gor-card`, `gor-wide`.
- Targets: LCP < 2.5 s, INP < 200 ms, CLS < 0.1.

## 8. Content still needed from the client

Placeholders are shown until replaced: phone `+91 XXXXX XXXXX`, email `info@yourdomain.com`, address, social links, product photos (CSS pack placeholders show until a product image is set), facility and farmer photography, leadership bios, certifications, founder's note, the exact scope of the ICAR-IIMR collaboration and permission to use its name, confirmed export markets (edit `template-parts/trade-map.php`), and the catalogue PDF. The home page market list and the Marathi farmer quote are illustrative.

## 9. Updating the motion libraries (developers)

```bash
npm i gsap lenis
cp node_modules/gsap/dist/{gsap.min.js,ScrollTrigger.min.js} node_modules/lenis/dist/lenis.min.js assets/vendor/
```
GSAP is free for commercial use (Webflow-supported standard licence). Lenis is MIT.

## 10. File map

```
guruswad-ocean/
├── style.css · functions.php · theme.json · screenshot.png
├── header.php · footer.php · front-page.php · index.php · page.php · single.php · 404.php
├── archive-gor_brand.php · single-gor_brand.php · woocommerce.php
├── page-templates/   about · manufacturing · export · farmers · investment · contact
├── template-parts/   cta-band · enquiry-form · rfq-form · trade-map
├── inc/              setup · customizer · template-tags · post-types · enquiries · i18n · woocommerce · icons
├── assets/css/       fonts.css · main.css · woocommerce.css
├── assets/js/        main.js
├── assets/vendor/    gsap · ScrollTrigger · lenis
├── assets/fonts/     Fraunces · Manrope · Tiro Devanagari Marathi · Mukta (woff2)
├── assets/img/       MilletRush logos (colour / white / wordmark) · hero-millet.svg
└── languages/        guruswad.pot · mr.po · mr.mo
```
