/* ==========================================================================
   Guruswad Ocean — admin panel (static preview)

   On the live site every screen here is a WordPress screen: a list table, a
   post editor, a Customizer section. There is no server behind a static page,
   so this stands those screens up in the browser and keeps the data in
   localStorage, which means every module is genuinely clickable and editable
   — add, edit, delete, search, export — rather than a picture of one.

   Nothing here ships to WordPress. The seed data is the same data the theme
   seeds (inc/markets.php, inc/enquiries.php, the product pages), so what the
   client clicks through matches what they will see after install.
   ========================================================================== */
(function () {
  'use strict';

  var STORE = 'gor_admin_data_v1';
  var $ = function (s, r) { return (r || document).querySelector(s); };
  var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };

  function esc(s) {
    return String(s === undefined || s === null ? '' : s)
      .replace(/&/g, '&amp;').replace(/</g, '&lt;')
      .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }

  /* --------------------------------------------------------------- storage */

  function readAll() {
    try { return JSON.parse(localStorage.getItem(STORE) || '{}') || {}; }
    catch (e) { return {}; }
  }

  function writeAll(d) {
    try { localStorage.setItem(STORE, JSON.stringify(d)); } catch (e) {}
  }

  function rows(key) {
    var d = readAll();
    if (!d[key]) {
      d[key] = JSON.parse(JSON.stringify(SEED[key] || []));
      writeAll(d);
    }
    return d[key];
  }

  function saveRows(key, list) {
    var d = readAll();
    d[key] = list;
    writeAll(d);
  }

  function settings(key) {
    var d = readAll();
    if (!d[key]) { d[key] = Object.assign({}, SEED[key] || {}); writeAll(d); }
    return d[key];
  }

  function uid() {
    return 'r' + Date.now().toString(36) + Math.random().toString(36).slice(2, 6);
  }

  /* ------------------------------------------------------------ seed data */

  var PRODUCTS = [
    { id: 'p1', name: 'Sorghum Puffs — Classic Salted', sku: 'MR-CS-70', price: 45, stock: 240, pack: '70 g', cat: 'Sorghum Puffs', status: 'Published' },
    { id: 'p2', name: 'Sorghum Puffs — Peri-Peri', sku: 'MR-PP-70', price: 45, stock: 180, pack: '70 g', cat: 'Sorghum Puffs', status: 'Published' },
    { id: 'p3', name: 'Sorghum Puffs — Desi Masala', sku: 'MR-DM-70', price: 45, stock: 150, pack: '70 g', cat: 'Sorghum Puffs', status: 'Published' },
    { id: 'p4', name: 'Sorghum Puffs — Tangy Tomato', sku: 'MR-TT-70', price: 45, stock: 96, pack: '70 g', cat: 'Sorghum Puffs', status: 'Published' },
    { id: 'p5', name: 'Pearl Millet Flour', sku: 'MR-BF-1K', price: 120, stock: 72, pack: '1 kg', cat: 'Millet Flours', status: 'Published' },
    { id: 'p6', name: 'Sorghum Flour', sku: 'MR-JF-1K', price: 110, stock: 64, pack: '1 kg', cat: 'Millet Flours', status: 'Published' }
  ];

  var SEED = {
    enquiries: [
      { id: 'e1', date: '2026-11-19', name: 'Rajesh Kulkarni', company: 'Shree Traders', email: 'rajesh@example.com', phone: '+91 98XXX XXXXX', country: 'India', type: 'bulk', message: 'Need 200 cartons of Classic Salted for Diwali retail. Please share slab pricing.', status: 'New' },
      { id: 'e2', date: '2026-11-18', name: 'Amina Al-Faris', company: 'Gulf Foods LLC', email: 'amina@example.com', phone: '+971 5X XXX XXXX', country: 'UAE', type: 'export', message: 'Requesting RFQ for one 20ft container of sorghum puffs, mixed flavours. Need HS code and shelf life.', status: 'New' },
      { id: 'e3', date: '2026-11-18', name: 'Daniel Mercer', company: 'Northfield Capital', email: 'daniel@example.com', phone: '+44 7XXX XXXXXX', country: 'United Kingdom', type: 'investor', message: 'Interested in the millet processing expansion. Could you share the information memorandum?', status: 'New' },
      { id: 'e4', date: '2026-11-15', name: 'Priya Deshmukh', company: '', email: 'priya@example.com', phone: '+91 97XXX XXXXX', country: 'India', type: 'product', message: 'Is the Pearl Millet Flour stone-ground and gluten-free?', status: 'Replied' },
      { id: 'e5', date: '2026-11-12', name: 'Hiroshi Tanaka', company: 'Asano Trading', email: 'hiroshi@example.com', phone: '+81 XX XXXX XXXX', country: 'Japan', type: 'import', message: 'Looking to import millet grain. What volumes can you commit to quarterly?', status: 'Replied' },
      { id: 'e6', date: '2026-11-08', name: 'Sunita Pawar', company: 'Pawar Agro FPO', email: 'sunita@example.com', phone: '+91 90XXX XXXXX', country: 'India', type: 'partner', message: 'Our FPO would like to supply sorghum. How do we join the network?', status: 'Closed' },
      { id: 'e7', date: '2026-11-05', name: 'Mark Oyelaran', company: '', email: 'mark@example.com', phone: '+234 XXX XXX XXXX', country: 'Nigeria', type: 'general', message: 'Do you ship to West Africa?', status: 'Closed' }
    ],

    products: PRODUCTS,

    orders: [
      { id: 'o1', ref: '#1041', date: '2026-11-19', customer: 'Priya Deshmukh', items: '2 × Classic Salted, 1 × Bajra Flour', total: 210, payment: 'UPI', status: 'Processing' },
      { id: 'o2', ref: '#1040', date: '2026-11-18', customer: 'Anil Shinde', items: '6 × Peri-Peri', total: 270, payment: 'Card', status: 'Shipped' },
      { id: 'o3', ref: '#1039', date: '2026-11-16', customer: 'Meera Joshi', items: '1 × Sorghum Flour', total: 110, payment: 'Net banking', status: 'Completed' },
      { id: 'o4', ref: '#1038', date: '2026-11-14', customer: 'Ravi Kadam', items: '4 × Desi Masala, 2 × Tangy Tomato', total: 270, payment: 'UPI', status: 'Completed' }
    ],

    pages: [
      { id: 'pg1', title: 'Home', slug: 'index.html', status: 'Published', updated: '2026-11-20' },
      { id: 'pg2', title: 'About Us', slug: 'about-us.html', status: 'Published', updated: '2026-11-19' },
      { id: 'pg3', title: 'Products', slug: 'shop.html', status: 'Published', updated: '2026-11-20' },
      { id: 'pg4', title: 'Manufacturing & Processing', slug: 'manufacturing.html', status: 'Published', updated: '2026-11-19' },
      { id: 'pg5', title: 'Import & Export', slug: 'import-export.html', status: 'Published', updated: '2026-11-19' },
      { id: 'pg6', title: 'Our Brands', slug: 'brands.html', status: 'Published', updated: '2026-11-18' },
      { id: 'pg7', title: 'Farmers & FPO Network', slug: 'farmers-fpo-network.html', status: 'Published', updated: '2026-11-18' },
      { id: 'pg8', title: 'Investment & Partnership', slug: 'investment-partnership.html', status: 'Published', updated: '2026-11-18' },
      { id: 'pg9', title: 'News & Updates', slug: 'news.html', status: 'Published', updated: '2026-11-17' },
      { id: 'pg10', title: 'Contact Us', slug: 'contact-us.html', status: 'Published', updated: '2026-11-17' },
      { id: 'pg11', title: 'मराठी — मुख्यपृष्ठ', slug: 'mr.html', status: 'Published', updated: '2026-11-17' },
      { id: 'pg12', title: 'Privacy Policy', slug: 'privacy-policy.html', status: 'Draft', updated: '2026-11-16' },
      { id: 'pg13', title: 'Terms', slug: 'terms.html', status: 'Draft', updated: '2026-11-16' },
      { id: 'pg14', title: 'Shipping & Returns', slug: 'shipping-returns.html', status: 'Draft', updated: '2026-11-16' }
    ],

    news: [
      { id: 'n1', title: 'MilletRush sorghum puffs reach UAE retail shelves', date: '2026-11-14', status: 'Published', excerpt: 'Our first consignment of Classic Salted and Peri-Peri has cleared customs in Dubai.' },
      { id: 'n2', title: 'New stone mill commissioned for millet flours', date: '2026-10-28', status: 'Published', excerpt: 'A slow-speed stone mill keeps flour temperature down and aroma intact.' },
      { id: 'n3', title: '650 farmers now in the sourcing network', date: '2026-10-09', status: 'Published', excerpt: 'Across Maharashtra, with 50 Women Self-Help Groups and 50 small entrepreneurs.' },
      { id: 'n4', title: 'Export catalogue — awaiting final approval', date: '2026-11-18', status: 'Draft', excerpt: 'Draft held until pricing and HS codes are confirmed.' }
    ],

    brands: [
      { id: 'b1', name: 'MilletRush', tagline: 'Everyday goodness of millets', products: 6, status: 'Live' }
    ],

    markets: [
      { id: 'm1', name: 'United Kingdom', lat: 51.51, lng: -0.13, status: 'entering' },
      { id: 'm2', name: 'Europe', lat: 51.92, lng: 4.48, status: 'entering' },
      { id: 'm3', name: 'USA', lat: 40.71, lng: -74.01, status: 'entering' },
      { id: 'm4', name: 'Saudi Arabia', lat: 24.71, lng: 46.68, status: 'serving' },
      { id: 'm5', name: 'UAE', lat: 25.20, lng: 55.27, status: 'serving' },
      { id: 'm6', name: 'South-East Asia', lat: 1.35, lng: 103.82, status: 'serving' },
      { id: 'm7', name: 'Australia', lat: -33.87, lng: 151.21, status: 'entering' }
    ],

    stories: [
      { id: 's1', name: 'Sunita Pawar', role: 'Women SHG lead', village: 'Jalna district', status: 'Published' },
      { id: 's2', name: 'Bhaskar Jadhav', role: 'Sorghum farmer', village: 'Beed district', status: 'Published' },
      { id: 's3', name: 'Kavita More', role: 'SHG — packing unit', village: 'Latur district', status: 'Published' },
      { id: 's4', name: 'Ramesh Shelke', role: 'Pearl millet farmer', village: 'Osmanabad district', status: 'Published' },
      { id: 's5', name: 'Anjali Gaikwad', role: 'Small entrepreneur', village: 'Nanded district', status: 'Draft' },
      { id: 's6', name: 'Dattatray Kale', role: 'FPO board member', village: 'Parbhani district', status: 'Draft' }
    ],

    leadership: [
      { id: 'l1', name: '— to be supplied —', role: 'Managing Director', bio: '', status: 'Placeholder' },
      { id: 'l2', name: '— to be supplied —', role: 'Director, Operations', bio: '', status: 'Placeholder' }
    ],

    certs: [
      { id: 'c1', name: 'FSSAI licence', issuer: 'Food Safety and Standards Authority of India', number: '— to be supplied —', status: 'Placeholder' },
      { id: 'c2', name: 'ICAR–IIMR technology collaboration', issuer: 'Indian Institute of Millets Research', number: '— written basis pending —', status: 'Placeholder' }
    ],

    business: {
      company_name: 'Guruswad Ocean Private Limited',
      phone: '+91 XXXXX XXXXX',
      whatsapp: '91XXXXXXXXXX',
      email: 'info@yourdomain.com',
      address: 'Registered office, Maharashtra, India',
      catalogue_pdf: ''
    },

    routing: {
      mail_general: '', mail_product: '', mail_export: '', mail_bulk: '',
      mail_import: '', mail_investor: '', mail_partner: ''
    },

    hero: {
      hero_video: 'assets/video/hero-millet.mp4',
      hero_poster: 'assets/img/photo/hero-sorghum-field.jpg',
      stat_farmers: 650, stat_shg: 50, stat_entre: 50, stat_fpo: 1
    },

    analytics: { ga4_id: '', gsc_verify: '' },

    /* §13 and §14 both require English and Marathi to be editable in the
       panel. Each row is one piece of site copy in both languages. */
    i18n: [
      { id: 't1', area: 'Home hero', en: 'From Soil to Signature.', mr: 'मातीपासून स्वाक्षरीपर्यंत.', status: 'Published' },
      { id: 't2', area: 'Home hero subtitle', en: 'Millets grown by our farmer network, crafted for tables across the world.', mr: 'आमच्या शेतकरी नेटवर्कने पिकवलेली भरडधान्ये, जगभरातील टेबलांसाठी.', status: 'Published' },
      { id: 't3', area: 'Menu — Products', en: 'Products', mr: 'उत्पादने', status: 'Published' },
      { id: 't4', area: 'Menu — About', en: 'About', mr: 'आमच्याविषयी', status: 'Published' },
      { id: 't5', area: 'Menu — Farmers & FPO', en: 'Farmers & FPO', mr: 'शेतकरी व एफपीओ', status: 'Published' },
      { id: 't6', area: 'Button — Request a quotation', en: 'Request a quotation', mr: 'दरपत्रक मागवा', status: 'Published' },
      { id: 't7', area: 'Button — Bulk order enquiry', en: 'Bulk order enquiry', mr: 'मोठ्या ऑर्डरसाठी चौकशी', status: 'Published' },
      { id: 't8', area: 'Product — price line', en: 'Price on request', mr: 'दर विनंतीनुसार', status: 'Published' },
      { id: 't9', area: 'Farmers — impact line', en: '650 farmers, 50 Women Self-Help Groups, 50 small entrepreneurs', mr: '६५० शेतकरी, ५० महिला बचत गट, ५० लघुउद्योजक', status: 'Published' },
      { id: 't10', area: 'Footer — company line', en: 'A vertically integrated millet company connecting Maharashtra’s farmers to tables across the world.', mr: 'महाराष्ट्रातील शेतकऱ्यांना जगभरातील टेबलांशी जोडणारी एकात्मिक भरडधान्य कंपनी.', status: 'Published' },
      { id: 't11', area: 'Product — Classic Salted name', en: 'Sorghum Puffs — Classic Salted', mr: 'ज्वारी पफ्स — क्लासिक सॉल्टेड', status: 'Draft' },
      { id: 't12', area: 'Product — Pearl Millet Flour name', en: 'Pearl Millet Flour', mr: 'बाजरी पीठ', status: 'Draft' }
    ]
  };

  /* Generated from the real markup by build_media.py — which pages each
     file appears on, and the size the markup asks for. Regenerate rather
     than hand-editing, or it drifts away from the site. */
  var MEDIA = [
    {
      "f": "facility-exterior.jpg",
      "g": "Manufacturing",
      "where": [],
      "size": ""
    },
    {
      "f": "farm-to-export.jpg",
      "g": "Import & Export",
      "where": [
        "Import & Export",
        "Investment"
      ],
      "size": "1600 x 900"
    },
    {
      "f": "farmer-portrait.jpg",
      "g": "Farmers & FPO",
      "where": [
        "Home",
        "Marathi home"
      ],
      "size": "1200 x 1400"
    },
    {
      "f": "farmer-proud.jpg",
      "g": "Farmers & FPO",
      "where": [],
      "size": ""
    },
    {
      "f": "farmers-collage.jpg",
      "g": "Farmers & FPO",
      "where": [
        "Farmers & FPO"
      ],
      "size": "1600 x 900"
    },
    {
      "f": "farmers-group.jpg",
      "g": "Farmers & FPO",
      "where": [
        "Farmers & FPO"
      ],
      "size": ""
    },
    {
      "f": "field-sunrise.jpg",
      "g": "Supporting photography",
      "where": [
        "Home",
        "Marathi home",
        "product pages"
      ],
      "size": "1200 x 1400"
    },
    {
      "f": "field-sunset.jpg",
      "g": "Supporting photography",
      "where": [
        "product pages"
      ],
      "size": ""
    },
    {
      "f": "footer-field.jpg",
      "g": "Footer",
      "where": [
        "Not found",
        "About Us",
        "MilletRush",
        "Our Brands",
        "Contact",
        "Farmers & FPO",
        "Import & Export",
        "Home",
        "Investment",
        "Manufacturing",
        "Marathi home",
        "News",
        "Privacy",
        "product pages",
        "Shipping",
        "Products",
        "Terms"
      ],
      "size": ""
    },
    {
      "f": "grains-still-life.jpg",
      "g": "Supporting photography",
      "where": [
        "product pages"
      ],
      "size": ""
    },
    {
      "f": "grains-to-market.jpg",
      "g": "Import & Export",
      "where": [
        "Import & Export"
      ],
      "size": "1600 x 900"
    },
    {
      "f": "headquarters.jpg",
      "g": "Corporate",
      "where": [
        "About Us",
        "Contact",
        "News",
        "Privacy",
        "Shipping",
        "Terms"
      ],
      "size": ""
    },
    {
      "f": "hero-soil-to-signature.jpg",
      "g": "Hero & banners",
      "where": [
        "Not found",
        "MilletRush",
        "Home",
        "Marathi home",
        "Products"
      ],
      "size": ""
    },
    {
      "f": "hero-sorghum-field.jpg",
      "g": "Hero & banners",
      "where": [],
      "size": ""
    },
    {
      "f": "pack-bajra-flour.jpg",
      "g": "Product packs",
      "where": [
        "MilletRush",
        "Home",
        "Marathi home",
        "product pages",
        "Products"
      ],
      "size": "1000 x 1250"
    },
    {
      "f": "pack-classic-salted.jpg",
      "g": "Product packs",
      "where": [
        "MilletRush",
        "Home",
        "Marathi home",
        "product pages",
        "Products"
      ],
      "size": "1000 x 1250"
    },
    {
      "f": "pack-jowar-flour.jpg",
      "g": "Product packs",
      "where": [
        "product pages",
        "Products"
      ],
      "size": "1000 x 1250"
    },
    {
      "f": "pack-peri-peri.jpg",
      "g": "Product packs",
      "where": [
        "MilletRush",
        "Home",
        "Marathi home",
        "product pages",
        "Products"
      ],
      "size": "1000 x 1250"
    },
    {
      "f": "pack-pudina.jpg",
      "g": "Product packs",
      "where": [
        "MilletRush",
        "Home",
        "Marathi home",
        "product pages",
        "Products"
      ],
      "size": "1000 x 1250"
    },
    {
      "f": "pack-tangy-tomato.jpg",
      "g": "Product packs",
      "where": [
        "product pages",
        "Products"
      ],
      "size": "1000 x 1250"
    },
    {
      "f": "port-sunrise.jpg",
      "g": "Import & Export",
      "where": [
        "Import & Export"
      ],
      "size": "1600 x 900"
    },
    {
      "f": "processing-factory.jpg",
      "g": "Manufacturing",
      "where": [],
      "size": ""
    },
    {
      "f": "processing-grading.jpg",
      "g": "Manufacturing",
      "where": [
        "Home",
        "Marathi home",
        "product pages"
      ],
      "size": "1200 x 1400"
    },
    {
      "f": "processing-line.jpg",
      "g": "Manufacturing",
      "where": [
        "Home",
        "Manufacturing",
        "Marathi home",
        "product pages"
      ],
      "size": "1600 x 900"
    },
    {
      "f": "quality-lab.jpg",
      "g": "Manufacturing",
      "where": [
        "Manufacturing"
      ],
      "size": "1600 x 900"
    },
    {
      "f": "range-trio.jpg",
      "g": "Supporting photography",
      "where": [
        "MilletRush",
        "Our Brands",
        "product pages",
        "Products"
      ],
      "size": ""
    },
    {
      "f": "snack-bowl.jpg",
      "g": "Supporting photography",
      "where": [
        "Home",
        "Marathi home",
        "product pages"
      ],
      "size": "1200 x 1400"
    },
    {
      "f": "trade-logistics.jpg",
      "g": "Import & Export",
      "where": [],
      "size": ""
    },
    {
      "f": "trade-port.jpg",
      "g": "Import & Export",
      "where": [
        "Import & Export"
      ],
      "size": ""
    },
    {
      "f": "trade-terminal.jpg",
      "g": "Import & Export",
      "where": [
        "Home",
        "Marathi home"
      ],
      "size": "1200 x 1400"
    },
    {
      "f": "warehouse-dock.jpg",
      "g": "Import & Export",
      "where": [
        "Import & Export"
      ],
      "size": "1600 x 900"
    },
    {
      "f": "hero-millet.mp4",
      "g": "Hero & banners",
      "where": [
        "Home"
      ],
      "size": "1920 x 1080",
      "video": true
    },
    {
      "f": "footer-field.mp4",
      "g": "Footer",
      "where": [
        "Every page"
      ],
      "size": "1920 x 1080",
      "video": true
    }
  ];

  var GROUPS = ['Hero & banners', 'Product packs', 'Farmers & FPO', 'Manufacturing',
                'Import & Export', 'Corporate', 'Footer', 'Supporting photography'];

  /* The slots on the site. This is what the client reads when they want to
     know what to supply and where it will land. */
  var PLACEMENTS = [
    {
      "slot": "Home hero — film",
      "where": "Home, full screen behind the headline",
      "needs": "MP4, 1920 x 1080, under 5 MB, no sound",
      "now": "hero-millet.mp4"
    },
    {
      "slot": "Home hero — still",
      "where": "Shown while the film loads, and on slow connections",
      "needs": "JPG, 1920 x 1080 (16:9)",
      "now": "hero-sorghum-field.jpg"
    },
    {
      "slot": "Social share image",
      "where": "The picture WhatsApp, LinkedIn and Facebook show when the site is shared",
      "needs": "JPG, 1200 x 630 (1.91:1)",
      "now": "hero-soil-to-signature.jpg"
    },
    {
      "slot": "Footer — film",
      "where": "Behind the footer on every page",
      "needs": "MP4, 1920 x 1080, under 5 MB, no sound",
      "now": "footer-field.mp4"
    },
    {
      "slot": "Footer — still",
      "where": "Shown while the footer film loads",
      "needs": "JPG, 1920 x 1080 (16:9)",
      "now": "footer-field.jpg"
    },
    {
      "slot": "Product pack shot",
      "where": "Shop grid and the top of each product page",
      "needs": "JPG, 1000 x 1250 (4:5), pack upright and centred",
      "now": "pack-classic-salted.jpg"
    },
    {
      "slot": "Product gallery — second image",
      "where": "Thumbnail beside the pack shot",
      "needs": "JPG, 1000 x 1250 (4:5)",
      "now": "snack-bowl.jpg"
    },
    {
      "slot": "Product gallery — third image",
      "where": "Thumbnail beside the pack shot",
      "needs": "JPG, 1000 x 1250 (4:5)",
      "now": "grains-still-life.jpg"
    },
    {
      "slot": "Product gallery — fourth image",
      "where": "Thumbnail beside the pack shot",
      "needs": "JPG, 1000 x 1250 (4:5)",
      "now": "range-trio.jpg"
    },
    {
      "slot": "Farmers — network portrait",
      "where": "Farmers & FPO page, main image",
      "needs": "JPG, 1600 x 1000 (16:10)",
      "now": "farmers-group.jpg"
    },
    {
      "slot": "Farmers — SHG collage",
      "where": "Farmers & FPO page, impact section",
      "needs": "JPG, 1600 x 1000 (16:10)",
      "now": "farmers-collage.jpg"
    },
    {
      "slot": "Manufacturing — line",
      "where": "Manufacturing page and the Home journey strip",
      "needs": "JPG, 1600 x 1000 (16:10)",
      "now": "processing-line.jpg"
    },
    {
      "slot": "Manufacturing — quality lab",
      "where": "Manufacturing page, quality control section",
      "needs": "JPG, 1600 x 1000 (16:10)",
      "now": "quality-lab.jpg"
    },
    {
      "slot": "Import & Export — port",
      "where": "Import & Export page header",
      "needs": "JPG, 1600 x 1000 (16:10)",
      "now": "trade-port.jpg"
    },
    {
      "slot": "Import & Export — logistics",
      "where": "Import & Export page, infrastructure section",
      "needs": "JPG, 1600 x 1000 (16:10)",
      "now": "warehouse-dock.jpg"
    },
    {
      "slot": "Corporate — headquarters",
      "where": "About Us, Contact and the legal pages",
      "needs": "JPG, 1600 x 1000 (16:10)",
      "now": "headquarters.jpg"
    },
    {
      "slot": "Brand logo",
      "where": "Header, footer, mega menu and the admin sign-in",
      "needs": "SVG preferred, or PNG at 988 x 500 on a transparent background",
      "now": "milletrush-color.svg"
    },
    {
      "slot": "Favicon",
      "where": "Browser tab and bookmarks",
      "needs": "SVG, or PNG at 512 x 512",
      "now": "favicon.svg"
    },
    {
      "slot": "Export catalogue",
      "where": "Downloaded from Import & Export and the footer",
      "needs": "PDF, under 10 MB",
      "now": ""
    }
  ];

  var ENQUIRY_TYPES = {
    general: 'General enquiry', product: 'Product enquiry',
    export: 'Export / Request for Quotation', bulk: 'Bulk order (India)',
    import: 'Import enquiry', investor: 'Investor enquiry', partner: 'Business partnership'
  };

  /* ------------------------------------------------------------- modules */

  function sel(opts) { return { type: 'select', options: opts }; }

  var MODULES = {
    enquiries: {
      title: 'Enquiries', kind: 'list', key: 'enquiries',
      blurb: 'All seven enquiry types, with status and CSV export. On the live site these arrive from the website forms and are also emailed to the inbox set in Enquiry routing.',
      cols: [
        { k: 'date', label: 'Date', w: '96px' },
        { k: 'name', label: 'From', strong: true },
        { k: 'type', label: 'Type', map: ENQUIRY_TYPES },
        { k: 'country', label: 'Country' },
        { k: 'status', label: 'Status', badge: true }
      ],
      fields: [
        { k: 'date', label: 'Date', type: 'date' },
        { k: 'name', label: 'Name' }, { k: 'company', label: 'Company' },
        { k: 'email', label: 'Email', type: 'email' }, { k: 'phone', label: 'Phone / WhatsApp' },
        { k: 'country', label: 'Country' },
        { k: 'type', label: 'Enquiry type', control: sel(Object.keys(ENQUIRY_TYPES).map(function (k) { return { v: k, t: ENQUIRY_TYPES[k] }; })) },
        { k: 'message', label: 'Message', type: 'textarea' },
        { k: 'status', label: 'Status', control: sel(['New', 'Replied', 'Closed']) }
      ],
      csv: true, search: true,
      filters: { k: 'type', label: 'Type', map: ENQUIRY_TYPES }
    },

    products: {
      title: 'Products, prices & stock', kind: 'list', key: 'products',
      blurb: 'Add products, set prices and manage inventory. In WordPress this is WooCommerce → Products; the price and stock you set here are what the shop shows.',
      cols: [
        { k: 'name', label: 'Product', strong: true },
        { k: 'sku', label: 'SKU', w: '110px' },
        { k: 'cat', label: 'Category' },
        { k: 'price', label: 'Price', money: true, w: '90px' },
        { k: 'stock', label: 'Stock', w: '80px' },
        { k: 'status', label: 'Status', badge: true, w: '110px' }
      ],
      fields: [
        { k: 'name', label: 'Product name' }, { k: 'sku', label: 'SKU' },
        { k: 'cat', label: 'Category', control: sel(['Sorghum Puffs', 'Millet Flours', 'Millets & Grains', 'Processed Foods']) },
        { k: 'pack', label: 'Pack size' },
        { k: 'price', label: 'Price (₹)', type: 'number' },
        { k: 'stock', label: 'Stock on hand', type: 'number' },
        { k: 'status', label: 'Status', control: sel(['Published', 'Draft', 'Out of stock']) }
      ],
      csv: true, search: true
    },

    orders: {
      title: 'Orders', kind: 'list', key: 'orders',
      blurb: 'Customer orders, invoices and fulfilment. WooCommerce creates these from checkout; the GST invoice is generated per order.',
      cols: [
        { k: 'ref', label: 'Order', strong: true, w: '80px' },
        { k: 'date', label: 'Date', w: '96px' },
        { k: 'customer', label: 'Customer' },
        { k: 'items', label: 'Items' },
        { k: 'total', label: 'Total', money: true, w: '90px' },
        { k: 'status', label: 'Status', badge: true, w: '110px' }
      ],
      fields: [
        { k: 'ref', label: 'Order reference' }, { k: 'date', label: 'Date', type: 'date' },
        { k: 'customer', label: 'Customer' }, { k: 'items', label: 'Items', type: 'textarea' },
        { k: 'total', label: 'Total (₹)', type: 'number' },
        { k: 'payment', label: 'Payment method', control: sel(['UPI', 'Card', 'Net banking', 'Cash on delivery']) },
        { k: 'status', label: 'Status', control: sel(['Processing', 'Shipped', 'Completed', 'Refunded', 'Cancelled']) }
      ],
      csv: true, search: true
    },

    pages: {
      title: 'Pages', kind: 'list', key: 'pages',
      blurb: 'Add and edit site pages and their content blocks. Each row opens the page editor in WordPress.',
      cols: [
        { k: 'title', label: 'Title', strong: true },
        { k: 'slug', label: 'Address', link: true },
        { k: 'status', label: 'Status', badge: true, w: '110px' },
        { k: 'updated', label: 'Updated', w: '104px' }
      ],
      fields: [
        { k: 'title', label: 'Page title' }, { k: 'slug', label: 'Address (slug)' },
        { k: 'status', label: 'Status', control: sel(['Published', 'Draft', 'Private']) },
        { k: 'updated', label: 'Last updated', type: 'date' }
      ],
      search: true
    },

    news: {
      title: 'News & announcements', kind: 'list', key: 'news',
      blurb: 'Publish updates, recognitions and press. These appear on the News & Updates page.',
      cols: [
        { k: 'title', label: 'Headline', strong: true },
        { k: 'date', label: 'Date', w: '104px' },
        { k: 'status', label: 'Status', badge: true, w: '110px' }
      ],
      fields: [
        { k: 'title', label: 'Headline' }, { k: 'date', label: 'Date', type: 'date' },
        { k: 'excerpt', label: 'Summary', type: 'textarea' },
        { k: 'status', label: 'Status', control: sel(['Published', 'Draft']) }
      ],
      search: true
    },

    brands: {
      title: 'Brands', kind: 'list', key: 'brands',
      blurb: 'MilletRush and any future brand, each with its own world. Adding a brand creates its own section of the site.',
      cols: [
        { k: 'name', label: 'Brand', strong: true },
        { k: 'tagline', label: 'Tagline' },
        { k: 'products', label: 'Products', w: '90px' },
        { k: 'status', label: 'Status', badge: true, w: '100px' }
      ],
      fields: [
        { k: 'name', label: 'Brand name' }, { k: 'tagline', label: 'Tagline' },
        { k: 'products', label: 'Product count', type: 'number' },
        { k: 'status', label: 'Status', control: sel(['Live', 'Draft']) }
      ]
    },

    markets: {
      title: 'Export markets', kind: 'list', key: 'markets',
      blurb: 'Markets on the trade map. Latitude and longitude place the pin; status decides whether it reads as a market we serve or one we are entering.',
      cols: [
        { k: 'name', label: 'Market', strong: true },
        { k: 'lat', label: 'Latitude', w: '100px' },
        { k: 'lng', label: 'Longitude', w: '100px' },
        { k: 'status', label: 'Status', badge: true, w: '110px' }
      ],
      fields: [
        { k: 'name', label: 'Market name' },
        { k: 'lat', label: 'Latitude (−90 to 90)', type: 'number', step: 'any' },
        { k: 'lng', label: 'Longitude (−180 to 180)', type: 'number', step: 'any' },
        { k: 'status', label: 'Status', control: sel([{ v: 'serving', t: 'Serving' }, { v: 'entering', t: 'Entering' }]) }
      ]
    },

    stories: {
      title: 'Farmer & SHG stories', kind: 'list', key: 'stories',
      blurb: 'The impact stories shown across the Farmers & FPO pages.',
      cols: [
        { k: 'name', label: 'Name', strong: true },
        { k: 'role', label: 'Role' },
        { k: 'village', label: 'Location' },
        { k: 'status', label: 'Status', badge: true, w: '110px' }
      ],
      fields: [
        { k: 'name', label: 'Name' }, { k: 'role', label: 'Role' },
        { k: 'village', label: 'Village / district' },
        { k: 'story', label: 'Their story', type: 'textarea' },
        { k: 'status', label: 'Status', control: sel(['Published', 'Draft']) }
      ],
      search: true
    },

    leadership: {
      title: 'Leadership', kind: 'list', key: 'leadership',
      blurb: 'Board and management shown on About Us. Names, photographs and biographies come from the company — nothing is invented here.',
      cols: [
        { k: 'name', label: 'Name', strong: true },
        { k: 'role', label: 'Role' },
        { k: 'status', label: 'Status', badge: true, w: '120px' }
      ],
      fields: [
        { k: 'name', label: 'Full name' }, { k: 'role', label: 'Role / designation' },
        { k: 'bio', label: 'Biography', type: 'textarea' },
        { k: 'status', label: 'Status', control: sel(['Published', 'Draft', 'Placeholder']) }
      ]
    },

    certs: {
      title: 'Certifications', kind: 'list', key: 'certs',
      blurb: 'Badges and collaborations shown as proof. A certification is only published once its written basis is on file.',
      cols: [
        { k: 'name', label: 'Certification', strong: true },
        { k: 'issuer', label: 'Issued by' },
        { k: 'number', label: 'Reference' },
        { k: 'status', label: 'Status', badge: true, w: '120px' }
      ],
      fields: [
        { k: 'name', label: 'Certification' }, { k: 'issuer', label: 'Issued by' },
        { k: 'number', label: 'Licence / reference number' },
        { k: 'status', label: 'Status', control: sel(['Published', 'Draft', 'Placeholder']) }
      ]
    },

    i18n: {
      title: 'English & Marathi content', kind: 'list', key: 'i18n',
      blurb: 'Every piece of site copy in both languages. Edit the Marathi beside the English so the two never drift apart — a Marathi row left blank falls back to the English on the live site rather than showing nothing.',
      cols: [
        { k: 'area', label: 'Where it appears', strong: true },
        { k: 'en', label: 'English' },
        { k: 'mr', label: 'मराठी' },
        { k: 'status', label: 'Status', badge: true, w: '110px' }
      ],
      fields: [
        { k: 'area', label: 'Where it appears' },
        { k: 'en', label: 'English', type: 'textarea' },
        { k: 'mr', label: 'Marathi (मराठी)', type: 'textarea' },
        { k: 'status', label: 'Status', control: sel(['Published', 'Draft']) }
      ],
      csv: true, search: true,
      filters: { k: 'status', label: 'Status', map: { Published: 'Published', Draft: 'Draft' } }
    },

    media: { title: 'Media library', kind: 'media', blurb: 'Upload and manage photographs and video. These are the files the site is using today.' },

    hero: {
      title: 'Banners & hero media', kind: 'settings', key: 'hero',
      blurb: 'The home hero video or image, and the impact numbers beneath it.',
      fields: [
        { k: 'hero_video', label: 'Hero video (path or URL)' },
        { k: 'hero_poster', label: 'Hero poster image' },
        { k: 'stat_farmers', label: 'Farmers in the network', type: 'number' },
        { k: 'stat_shg', label: 'Women Self-Help Groups', type: 'number' },
        { k: 'stat_entre', label: 'Small entrepreneurs', type: 'number' },
        { k: 'stat_fpo', label: 'Farmer Producer Companies', type: 'number' }
      ]
    },

    business: {
      title: 'Business details', kind: 'settings', key: 'business',
      blurb: 'Phone, WhatsApp, email, address and the export catalogue. These appear in the header, the footer and every enquiry form.',
      fields: [
        { k: 'company_name', label: 'Company name' },
        { k: 'phone', label: 'Phone number' },
        { k: 'whatsapp', label: 'WhatsApp number (country code, no +)' },
        { k: 'email', label: 'Public email address', type: 'email' },
        { k: 'address', label: 'Registered address', type: 'textarea' },
        { k: 'catalogue_pdf', label: 'Export catalogue PDF' }
      ]
    },

    routing: {
      title: 'Enquiry routing', kind: 'settings', key: 'routing',
      blurb: 'Which inbox each of the seven enquiry types reaches. Leave one blank and it falls back to the public email address.',
      fields: [
        { k: 'mail_general', label: 'General enquiries', type: 'email' },
        { k: 'mail_product', label: 'Product enquiries', type: 'email' },
        { k: 'mail_export', label: 'Export enquiries / RFQ', type: 'email' },
        { k: 'mail_bulk', label: 'Bulk orders', type: 'email' },
        { k: 'mail_import', label: 'Import enquiries', type: 'email' },
        { k: 'mail_investor', label: 'Investor enquiries', type: 'email' },
        { k: 'mail_partner', label: 'Business partnerships', type: 'email' }
      ]
    },

    analytics: {
      title: 'Analytics & Search Console', kind: 'settings', key: 'analytics',
      blurb: 'Both IDs belong to the company’s own Google account, never the developer’s.',
      fields: [
        { k: 'ga4_id', label: 'Google Analytics 4 measurement ID (G-XXXXXXXXXX)' },
        { k: 'gsc_verify', label: 'Search Console verification code' }
      ]
    }
  };

  /* --------------------------------------------------------------- render */

  var root, state = { module: null, editing: null, q: '', filter: 'all' };

  function badgeClass(v) {
    var s = String(v).toLowerCase();
    if (s === 'new' || s === 'processing' || s === 'entering') { return 'is-warn'; }
    if (s === 'published' || s === 'live' || s === 'completed' || s === 'serving' || s === 'replied' || s === 'shipped') { return 'is-good'; }
    if (s === 'placeholder' || s === 'out of stock' || s === 'cancelled') { return 'is-bad'; }
    return 'is-mute';
  }

  function cellValue(row, col) {
    var v = row[col.k];
    if (col.map) { return col.map[v] || v; }
    if (col.money) { return '₹' + Number(v || 0).toLocaleString('en-IN'); }
    return v;
  }

  function listHTML(m) {
    var all = rows(m.key);
    var list = all.filter(function (r) {
      if (state.filter !== 'all' && m.filters && r[m.filters.k] !== state.filter) { return false; }
      if (!state.q) { return true; }
      return Object.keys(r).some(function (k) {
        return String(r[k]).toLowerCase().indexOf(state.q.toLowerCase()) !== -1;
      });
    });

    var head = m.cols.map(function (c) {
      return '<th' + (c.w ? ' style="width:' + c.w + '"' : '') + '>' + esc(c.label) + '</th>';
    }).join('') + '<th class="gor-t__act">Actions</th>';

    var body = list.length ? list.map(function (r) {
      return '<tr data-id="' + esc(r.id) + '">' + m.cols.map(function (c) {
        var v = cellValue(r, c);
        if (c.badge) { return '<td data-label="' + esc(c.label) + '"><span class="gor-badge ' + badgeClass(r[c.k]) + '">' + esc(v) + '</span></td>'; }
        if (c.link) { return '<td data-label="' + esc(c.label) + '"><a href="../' + esc(r[c.k]) + '" target="_blank" rel="noopener">' + esc(v) + '</a></td>'; }
        if (c.strong) { return '<td data-label="' + esc(c.label) + '"><button type="button" class="gor-t__title" data-edit="' + esc(r.id) + '">' + esc(v) + '</button></td>'; }
        return '<td data-label="' + esc(c.label) + '">' + esc(v) + '</td>';
      }).join('') +
        '<td class="gor-t__act"><button type="button" class="gor-link" data-edit="' + esc(r.id) + '">Edit</button>' +
        '<button type="button" class="gor-link gor-link--del" data-del="' + esc(r.id) + '">Delete</button></td></tr>';
    }).join('') : '<tr><td colspan="' + (m.cols.length + 1) + '" class="gor-t__empty">Nothing matches that yet.</td></tr>';

    var tools = '<div class="gor-tools">' +
      '<button type="button" class="gor-btn gor-btn--primary" data-new>Add new</button>' +
      (m.csv ? '<button type="button" class="gor-btn" data-csv>Download CSV</button>' : '') +
      (m.search ? '<input class="gor-search" type="search" placeholder="Search…" value="' + esc(state.q) + '" data-q>' : '') +
      '<span class="gor-tools__count">' + list.length + ' of ' + all.length + '</span>' +
      '</div>';

    var filters = '';
    if (m.filters) {
      filters = '<div class="gor-chips"><button type="button" class="' + (state.filter === 'all' ? 'is-active' : '') + '" data-filter="all">All</button>' +
        Object.keys(m.filters.map).map(function (k) {
          return '<button type="button" class="' + (state.filter === k ? 'is-active' : '') + '" data-filter="' + k + '">' + esc(m.filters.map[k]) + '</button>';
        }).join('') + '</div>';
    }

    return tools + filters +
      '<div class="gor-t__wrap"><table class="gor-t"><thead><tr>' + head + '</tr></thead><tbody>' + body + '</tbody></table></div>';
  }

  function fieldHTML(f, val) {
    var id = 'f_' + f.k;
    var input;
    if (f.control && f.control.type === 'select') {
      input = '<select id="' + id + '" name="' + f.k + '">' + f.control.options.map(function (o) {
        var v = typeof o === 'string' ? o : o.v, t = typeof o === 'string' ? o : o.t;
        return '<option value="' + esc(v) + '"' + (String(val) === String(v) ? ' selected' : '') + '>' + esc(t) + '</option>';
      }).join('') + '</select>';
    } else if (f.type === 'textarea') {
      input = '<textarea id="' + id + '" name="' + f.k + '" rows="4">' + esc(val) + '</textarea>';
    } else {
      input = '<input id="' + id + '" name="' + f.k + '" type="' + (f.type || 'text') + '"' +
        (f.step ? ' step="' + f.step + '"' : '') + ' value="' + esc(val) + '">';
    }
    return '<div class="gor-f"><label for="' + id + '">' + esc(f.label) + '</label>' + input + '</div>';
  }

  function formHTML(m, row) {
    var isNew = !row;
    row = row || {};
    return '<form class="gor-form" data-form>' +
      '<h3>' + (isNew ? 'Add new' : 'Edit') + '</h3>' +
      '<div class="gor-form__grid">' + m.fields.map(function (f) { return fieldHTML(f, row[f.k] === undefined ? '' : row[f.k]); }).join('') + '</div>' +
      '<div class="gor-form__actions">' +
      '<button type="submit" class="gor-btn gor-btn--primary">' + (isNew ? 'Add' : 'Save changes') + '</button>' +
      '<button type="button" class="gor-btn" data-cancel>Cancel</button>' +
      '</div></form>';
  }

  function settingsHTML(m) {
    var v = settings(m.key);
    return '<form class="gor-form gor-form--wide" data-settings>' +
      '<div class="gor-form__grid">' + m.fields.map(function (f) { return fieldHTML(f, v[f.k] === undefined ? '' : v[f.k]); }).join('') + '</div>' +
      '<div class="gor-form__actions"><button type="submit" class="gor-btn gor-btn--primary">Save</button>' +
      '<span class="gor-saved" data-saved hidden>Saved</span></div></form>';
  }

  /* A grid of filenames does not tell anyone which picture is where, or what
     to send when they want one changed. So the library leads with the slots —
     what each position on the site needs and what is sitting in it — and only
     then shows the files, each captioned with where it actually appears. */
  function mediaHTML() {
    var imgs = MEDIA.filter(function (m) { return !m.video; });
    var vids = MEDIA.filter(function (m) { return m.video; });
    var spare = imgs.filter(function (m) { return !m.where.length; }).length;

    var slots = PLACEMENTS.map(function (p) {
      var now = p.now
        ? '<code>' + esc(p.now) + '</code>'
        : '<span class="gor-badge is-bad">Not supplied</span>';
      return '<tr><td data-label="Position"><button type="button" class="gor-t__title" data-slot="' + esc(p.slot) + '">' +
        esc(p.slot) + '</button></td>' +
        '<td data-label="Where it appears">' + esc(p.where) + '</td>' +
        '<td data-label="What to supply"><strong class="gor-needs">' + esc(p.needs) + '</strong></td>' +
        '<td data-label="In use now">' + now + '</td>' +
        '<td class="gor-t__act"><button type="button" class="gor-link" data-upload>Replace</button></td></tr>';
    }).join('');

    function tile(m) {
      var src = m.video
        ? '../assets/img/photo/' + m.f.replace('hero-millet.mp4', 'hero-sorghum-field.jpg')
            .replace('footer-field.mp4', 'footer-field.jpg')
        : '../assets/img/photo/' + m.f;
      var where = m.where.length
        ? '<span class="gor-media__use">' + esc(m.where.join(' · ')) + '</span>'
        : '<span class="gor-media__use is-spare">Spare — not used yet</span>';
      return '<figure class="gor-media__item' + (m.video ? ' is-video' : '') + '">' +
        '<img src="' + src + '" alt="" loading="lazy" width="200" height="250">' +
        '<figcaption><b>' + esc(m.f) + '</b>' + where +
        (m.size ? '<span class="gor-media__dim">' + esc(m.size) + '</span>' : '') +
        '</figcaption></figure>';
    }

    var groups = '';
    GROUPS.forEach(function (g) {
      var inGroup = MEDIA.filter(function (m) { return m.g === g; });
      if (!inGroup.length) { return; }
      groups += '<h2>' + esc(g) + '</h2><div class="gor-media">' +
        inGroup.map(tile).join('') + '</div>';
    });

    return '<div class="gor-tools">' +
      '<button type="button" class="gor-btn gor-btn--primary" data-upload>Upload files</button>' +
      '<span class="gor-tools__count">' + imgs.length + ' images · ' + vids.length + ' videos' +
      (spare ? ' · ' + spare + ' spare' : '') + '</span></div>' +
      '<p class="gor-hint" data-upload-note hidden>On the live site this opens the WordPress media uploader. ' +
      'Upload the new file, then open the screen named in the <em>Where it appears</em> column and pick it there — ' +
      'uploading alone does not place an image.</p>' +

      '<h2>Where each image goes</h2>' +
      '<p class="description" style="margin-bottom:1.25rem">Every position on the site that takes a picture or a film. ' +
      'The third column is what to send us; the fourth is what is in that position today.</p>' +
      '<div class="gor-t__wrap"><table class="gor-t gor-t--slots"><thead><tr>' +
      '<th style="width:210px">Position</th><th>Where it appears</th>' +
      '<th style="width:260px">What to supply</th><th style="width:220px">In use now</th>' +
      '<th class="gor-t__act">Actions</th></tr></thead><tbody>' + slots + '</tbody></table></div>' +

      groups;
  }

  function screenHTML(id) {
    var m = MODULES[id];
    if (!m) { return ''; }
    var inner;
    if (m.kind === 'settings') { inner = settingsHTML(m); }
    else if (m.kind === 'media') { inner = mediaHTML(); }
    else { inner = state.editing !== null ? formHTML(m, state.editing === 'new' ? null : state.editing) : listHTML(m); }

    return '<div class="gor-admin">' +
      '<nav class="gor-crumb"><a href="#/" data-home>Dashboard</a> <span>/</span> ' + esc(m.title) + '</nav>' +
      '<div class="gor-masthead"><span class="gor-masthead__eyebrow">' +
      (m.kind === 'settings' ? 'Settings' : m.kind === 'media' ? 'Library' : 'Manage') +
      '</span><h1>' + esc(m.title) + '</h1><p>' + esc(m.blurb) + '</p></div>' +
      inner + '</div>';
  }

  /* ----------------------------------------------------------------- CSV */

  function downloadCSV(m) {
    var list = rows(m.key);
    var keys = m.fields.map(function (f) { return f.k; });
    var head = m.fields.map(function (f) { return f.label; });
    var lines = [head].concat(list.map(function (r) {
      return keys.map(function (k) { return r[k] === undefined ? '' : r[k]; });
    }));
    var csv = lines.map(function (row) {
      return row.map(function (c) {
        var s = String(c);
        // A leading =, +, - or @ is read as a formula by spreadsheet apps.
        if (/^[=+\-@]/.test(s)) { s = "'" + s; }
        return '"' + s.replace(/"/g, '""') + '"';
      }).join(',');
    }).join('\r\n');

    var blob = new Blob(['﻿' + csv], { type: 'text/csv;charset=utf-8;' });
    var a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = m.key + '-' + new Date().toISOString().slice(0, 10) + '.csv';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    setTimeout(function () { URL.revokeObjectURL(a.href); }, 1000);
  }

  /* --------------------------------------------------------------- router */

  function counts() {
    return {
      enquiries: rows('enquiries').filter(function (r) { return r.status === 'New'; }).length,
      products: rows('products').length,
      orders: rows('orders').filter(function (r) { return r.status === 'Processing'; }).length,
      news: rows('news').length, brands: rows('brands').length,
      stories: rows('stories').length, certs: rows('certs').length,
      markets: rows('markets').length, pages: rows('pages').length
    };
  }

  function paintCounts() {
    var c = counts();
    $$('[data-count]').forEach(function (el) {
      var n = c[el.getAttribute('data-count')];
      el.textContent = n === undefined ? '' : n;
      el.hidden = !n;
    });
    $$('[data-bubble]').forEach(function (el) {
      el.textContent = c.enquiries;
      el.hidden = !c.enquiries;
    });
    var w = $('[data-waiting]');
    if (w) { w.textContent = c.enquiries; }
  }

  function route() {
    var hash = (location.hash || '#/').replace(/^#\/?/, '');
    var id = hash.split('?')[0];
    state.module = MODULES[id] ? id : null;
    state.editing = null;
    state.q = '';
    state.filter = 'all';

    var home = $('#home'), screen = $('#screen');
    if (!state.module) {
      home.hidden = false;
      screen.hidden = true;
      screen.innerHTML = '';
    } else {
      home.hidden = true;
      screen.hidden = false;
      screen.innerHTML = screenHTML(state.module);
      screen.focus();
    }
    $$('[data-nav]').forEach(function (a) {
      a.classList.toggle('on', a.getAttribute('data-nav') === (state.module || 'dashboard'));
    });
    paintCounts();
    window.scrollTo(0, 0);
  }

  function redraw() {
    $('#screen').innerHTML = screenHTML(state.module);
    paintCounts();
  }

  /* --------------------------------------------------------------- events */

  function boot() {
    root = $('#screen');
    if (!root) { return; }

    document.addEventListener('click', function (e) {
      var t = e.target;
      var m = MODULES[state.module];

      if (t.closest('[data-home]')) { e.preventDefault(); location.hash = '#/'; return; }
      if (!m) { return; }

      if (t.closest('[data-new]')) { state.editing = 'new'; redraw(); return; }
      if (t.closest('[data-cancel]')) { state.editing = null; redraw(); return; }
      if (t.closest('[data-csv]')) { downloadCSV(m); return; }
      if (t.closest('[data-upload]')) {
        var note = $('[data-upload-note]');
        if (note) { note.hidden = !note.hidden; }
        return;
      }

      var ed = t.closest('[data-edit]');
      if (ed) {
        var id = ed.getAttribute('data-edit');
        state.editing = rows(m.key).filter(function (r) { return r.id === id; })[0] || null;
        redraw();
        return;
      }

      var del = t.closest('[data-del]');
      if (del) {
        var rid = del.getAttribute('data-del');
        var row = rows(m.key).filter(function (r) { return r.id === rid; })[0];
        if (row && window.confirm('Delete this entry? This cannot be undone.')) {
          saveRows(m.key, rows(m.key).filter(function (r) { return r.id !== rid; }));
          redraw();
        }
        return;
      }

      var fil = t.closest('[data-filter]');
      if (fil) { state.filter = fil.getAttribute('data-filter'); redraw(); return; }
    });

    document.addEventListener('input', function (e) {
      if (e.target.matches('[data-q]')) {
        state.q = e.target.value;
        var m = MODULES[state.module];
        var wrap = $('.gor-t__wrap');
        if (!m || !wrap) { return; }
        // Repaint only the table so the search box keeps focus and caret.
        var tmp = document.createElement('div');
        tmp.innerHTML = listHTML(m);
        wrap.innerHTML = tmp.querySelector('.gor-t__wrap').innerHTML;
        var c = $('.gor-tools__count');
        if (c) { c.textContent = tmp.querySelector('.gor-tools__count').textContent; }
      }
    });

    document.addEventListener('submit', function (e) {
      var m = MODULES[state.module];
      if (!m) { return; }

      if (e.target.matches('[data-form]')) {
        e.preventDefault();
        var data = {};
        $$('input, select, textarea', e.target).forEach(function (el) {
          if (el.name) { data[el.name] = el.type === 'number' ? (el.value === '' ? '' : Number(el.value)) : el.value; }
        });
        var list = rows(m.key);
        if (state.editing && state.editing !== 'new') {
          list = list.map(function (r) { return r.id === state.editing.id ? Object.assign({}, r, data) : r; });
        } else {
          data.id = uid();
          list = [data].concat(list);
        }
        saveRows(m.key, list);
        state.editing = null;
        redraw();
        return;
      }

      if (e.target.matches('[data-settings]')) {
        e.preventDefault();
        var v = {};
        $$('input, select, textarea', e.target).forEach(function (el) {
          if (el.name) { v[el.name] = el.type === 'number' ? Number(el.value) : el.value; }
        });
        var d = readAll();
        d[m.key] = v;
        writeAll(d);
        var saved = $('[data-saved]');
        if (saved) {
          saved.hidden = false;
          setTimeout(function () { saved.hidden = true; }, 2200);
        }
        paintCounts();
      }
    });

    window.addEventListener('hashchange', route);
    route();
  }

  window.GOR_ADMIN = { boot: boot, reset: function () { try { localStorage.removeItem(STORE); } catch (e) {} route(); } };
})();
