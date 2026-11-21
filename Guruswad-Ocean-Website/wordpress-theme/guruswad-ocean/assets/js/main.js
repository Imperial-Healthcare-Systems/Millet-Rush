/*!
 * Guruswad Ocean — motion & interaction layer
 * GSAP 3 + ScrollTrigger + Lenis. Every effect has a reduced-motion fallback.
 * Imperial Tech Innovations · Engineer the Future
 */
(function () {
  'use strict';

  var root = document.documentElement;
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var finePointer = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
  var hasGSAP = typeof window.gsap !== 'undefined' && typeof window.ScrollTrigger !== 'undefined';
  var cfg = window.GOR || {};

  if (!hasGSAP || reduce) root.classList.remove('js-motion');
  var motion = root.classList.contains('js-motion');

  var $ = function (s, c) { return (c || document).querySelector(s); };
  var $$ = function (s, c) { return Array.prototype.slice.call((c || document).querySelectorAll(s)); };

/* @@GOR-LAZY-VIDEO@@ */
  /* ---------------------------------------------------------------
     0. Decorative film — attached, never assumed

     The hero and footer films are texture, not content: the still frame
     underneath says the same thing, so the file is only fetched when it is
     worth the bytes, and only while it is on screen.

     "Worth the bytes" used to include a width test, which refused every
     viewport under 760px — so no phone played either film however good its
     wi-fi, while a laptop tethered to 3G played both. Width is a layout
     question. Affordability is a question about the connection, and that is
     what is asked here.
     --------------------------------------------------------------- */
  (function () {
    var vids = $$('video[data-lazy-video]');
    if (!vids.length) return;

    var mqMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    var mqData = window.matchMedia('(prefers-reduced-data: reduce)');

    function affordable() {
      /* The stylesheet hides the film under either of these. Asking again
         here is what stops the file being downloaded in order to be hidden. */
      if (mqMotion.matches || mqData.matches) return false;

      var c = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
      if (!c) return true;                      /* nothing reported — allow */
      if (c.saveData) return false;
      if (/^(slow-2g|2g|3g)$/.test(c.effectiveType || '')) return false;
      /* downlink is a rounded estimate in Mbit/s. Below 1.5 an eight-second
         loop lands after the section has already been scrolled past. */
      if (typeof c.downlink === 'number' && c.downlink > 0 && c.downlink < 1.5) return false;
      return true;
    }

    function attach(v) {
      if (v.dataset.lazyVideo === 'on') return;
      v.dataset.lazyVideo = 'on';
      $$('source[data-src]', v).forEach(function (s) {
        s.src = s.getAttribute('data-src');
        s.removeAttribute('data-src');
      });
      /* iOS wants muted as a property, not only as an attribute, before it
         will autoplay inline. Setting both costs nothing. */
      v.muted = true;
      v.defaultMuted = true;
      v.addEventListener('playing', function () { v.classList.add('is-playing'); });
      v.load();
    }

    function play(v) {
      var p = v.play();
      /* Autoplay can still be refused — iOS Low Power Mode, a browser policy.
         The still is already on screen underneath, so there is nothing to do
         and nothing to tell the visitor. */
      if (p && p.catch) p.catch(function () {});
    }

    if (!('IntersectionObserver' in window)) {
      if (affordable()) vids.forEach(function (v) { attach(v); play(v); });
      return;
    }

    /* The observer is deliberately never disconnected. A film that has
       scrolled away is paused instead of left decoding behind the page, which
       on a phone is the difference between a warm battery and a flat one. */
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        var v = e.target;
        v.dataset.inView = e.isIntersecting ? '1' : '0';
        if (!e.isIntersecting) { if (!v.paused) v.pause(); return; }
        if (!affordable()) return;
        attach(v);
        if (document.visibilityState !== 'hidden') play(v);
      });
    }, { rootMargin: '200px 0px' });
    vids.forEach(function (v) { io.observe(v); });

    /* Backgrounded tab: stop decoding. On return, resume only what is still
       on screen — the observer will not fire again on its own. */
    document.addEventListener('visibilitychange', function () {
      var hidden = document.visibilityState === 'hidden';
      vids.forEach(function (v) {
        if (v.dataset.lazyVideo !== 'on') return;
        if (hidden) { if (!v.paused) v.pause(); }
        else if (v.dataset.inView === '1') play(v);
      });
    });
  })();

  /* ---------------------------------------------------------------
     1. Smooth scroll (Lenis) — desktop only, synced to ScrollTrigger
     --------------------------------------------------------------- */
  var lenis = null;
  if (motion && finePointer && typeof window.Lenis !== 'undefined') {
    lenis = new window.Lenis({ duration: 1.15, easing: function (t) { return Math.min(1, 1.001 - Math.pow(2, -10 * t)); }, smoothWheel: true });
    lenis.on('scroll', ScrollTrigger.update);
    gsap.ticker.add(function (time) { lenis.raf(time * 1000); });
    gsap.ticker.lagSmoothing(0);
    root.classList.add('lenis');
  }
  if (hasGSAP) gsap.registerPlugin(ScrollTrigger);

  /* ---------------------------------------------------------------
     2. Header: solid after hero, hide on scroll down
     --------------------------------------------------------------- */
  var header = $('.site-header');
  var lastY = 0;
  function onScroll() {
    var y = window.scrollY || window.pageYOffset;
    // The floating WhatsApp button competes with the header's own CTA at the
    // top of the page, so it only appears once the reader is past the hero.
    document.body.classList.toggle('at-top', y < 220);
    if (!header) return;
    header.classList.toggle('is-solid', y > 40);
    if (!document.body.classList.contains('menu-open')) header.classList.toggle('is-hidden', y > lastY && y > 400);
    lastY = y;
  }

  /* ...and it steps aside again over the footer, where the same contacts are
     already listed in full. */
  (function footerWatch() {
    var foot = $('.site-footer');
    if (!foot || !('IntersectionObserver' in window)) return;
    new IntersectionObserver(function (entries) {
      document.body.classList.toggle('at-footer', entries[0].isIntersecting);
    }, { rootMargin: '0px 0px -10% 0px' }).observe(foot);
  })();
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  /* ---------------------------------------------------------------
     3. Full-screen menu
     --------------------------------------------------------------- */
  var toggle = $('.menu-toggle');
  $$('.mega__list li').forEach(function (li, i) { li.style.setProperty('--i', i); });
  function setMenu(open) {
    document.body.classList.toggle('menu-open', open);
    if (toggle) toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    var mega = $('.mega'); if (mega) { mega.setAttribute('aria-hidden', open ? 'false' : 'true'); mega.inert = !open; }
    if (lenis) { open ? lenis.stop() : lenis.start(); }
  }
  if (toggle) toggle.addEventListener('click', function () { setMenu(!document.body.classList.contains('menu-open')); });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') { setMenu(false); closeCart(); } });

  /* ---------------------------------------------------------------
     4. Helpers: split into words / lines
     --------------------------------------------------------------- */
  function splitWords(el) {
    if (el.dataset.split) return $$('.word', el);
    var nodes = Array.prototype.slice.call(el.childNodes);
    el.innerHTML = '';
    nodes.forEach(function (node) {
      if (node.nodeType === 3) {
        node.textContent.split(/(\s+)/).forEach(function (part) {
          if (!part) return;
          if (/^\s+$/.test(part)) { el.appendChild(document.createTextNode(part)); return; }
          var s = document.createElement('span'); s.className = 'word'; s.textContent = part; el.appendChild(s);
        });
      } else if (node.nodeType === 1) {
        var wrap = node.cloneNode(false);
        splitWords(Object.assign(node, {})); // split inside element
        Array.prototype.slice.call(node.childNodes).forEach(function (c) { wrap.appendChild(c); });
        el.appendChild(wrap);
      }
    });
    el.dataset.split = '1';
    return $$('.word', el);
  }

  /* ---------------------------------------------------------------
     5. Hero entrance
     --------------------------------------------------------------- */
  function heroIntro() {
    var lines = $$('.hero__title .line > span');
    if (!motion) return;
    var tl = gsap.timeline({ defaults: { ease: 'expo.out' }, delay: .15 });
    tl.to(lines, { y: 0, duration: 1.5, stagger: .12 })
      .from('.hero .eyebrow, .hero__sub, .hero .btn-row, .hero__meta', { y: 30, autoAlpha: 0, duration: 1.2, stagger: .1 }, '-=1.1')
      .from('.hero__art svg', { autoAlpha: 0, scale: .92, rotate: -4, duration: 2.2 }, 0)
      .from('.hero__art .grain', { scale: 0, transformOrigin: 'center', duration: .9, stagger: { each: .012, from: 'random' }, ease: 'back.out(2)' }, .2);
    // gentle parallax on scroll out
    gsap.to('.hero__art', { yPercent: 18, ease: 'none', scrollTrigger: { trigger: '.hero', start: 'top top', end: 'bottom top', scrub: true } });
    gsap.to('.hero__inner', { yPercent: -12, autoAlpha: .2, ease: 'none', scrollTrigger: { trigger: '.hero', start: 'top top', end: 'bottom top', scrub: true } });
  }

  /* Hero grain particles (canvas, ≤ 40 particles, paused off-screen) */
  function grainCanvas() {
    var c = $('.hero__canvas'); if (!c || reduce) return;
    var ctx = c.getContext('2d'), dpr = Math.min(window.devicePixelRatio || 1, 2), w, h, parts = [], running = true;
    var colors = ['#e39a22', '#f1c47a', '#c68d4f', '#8db300', '#631c0a'];
    function size() { w = c.clientWidth; h = c.clientHeight; c.width = w * dpr; c.height = h * dpr; ctx.setTransform(dpr, 0, 0, dpr, 0, 0); }
    function spawn(p, init) {
      p.x = Math.random() * w; p.y = init ? Math.random() * h : h + 10;
      p.r = 1.5 + Math.random() * 3.2; p.vy = .15 + Math.random() * .45; p.vx = (Math.random() - .5) * .25;
      p.a = .25 + Math.random() * .55; p.c = colors[(Math.random() * colors.length) | 0]; p.t = Math.random() * 6.28;
      return p;
    }
    size(); for (var i = 0; i < 38; i++) parts.push(spawn({}, true));
    window.addEventListener('resize', size);
    function draw() {
      if (!running) return;
      ctx.clearRect(0, 0, w, h);
      parts.forEach(function (p) {
        p.t += .01; p.y -= p.vy; p.x += p.vx + Math.sin(p.t) * .2;
        if (p.y < -10) spawn(p, false);
        ctx.globalAlpha = p.a; ctx.fillStyle = p.c;
        ctx.beginPath(); ctx.ellipse(p.x, p.y, p.r, p.r * 1.25, p.t, 0, Math.PI * 2); ctx.fill();
      });
      requestAnimationFrame(draw);
    }
    draw();
    if ('IntersectionObserver' in window) new IntersectionObserver(function (e) { var v = e[0].isIntersecting; if (v && !running) { running = true; draw(); } running = v; }).observe(c);
  }

  /* ---------------------------------------------------------------
     6. Generic reveals
     --------------------------------------------------------------- */
  function reveals() {
    if (!motion) return;
    ScrollTrigger.batch('[data-reveal]', {
      start: 'top 88%', once: true,
      onEnter: function (els) { gsap.to(els, { autoAlpha: 1, y: 0, duration: 1.1, ease: 'expo.out', stagger: .08, overwrite: true }); }
    });
    $$('[data-clip]').forEach(function (el) {
      gsap.timeline({ scrollTrigger: { trigger: el, start: 'top 85%', once: true } })
        .to(el, { clipPath: 'inset(0% 0 0 0)', duration: 1.4, ease: 'expo.inOut' })
        .to(el.children, { scale: 1, duration: 1.8, ease: 'expo.out' }, '<.1');
    });
    $$('[data-speed]').forEach(function (el) {
      var s = parseFloat(el.dataset.speed) || .2;
      gsap.to(el, { yPercent: -s * 100, ease: 'none', scrollTrigger: { trigger: el.closest('section') || el, start: 'top bottom', end: 'bottom top', scrub: true } });
    });
    // split-text headings
    $$('[data-split]').forEach(function (el) {
      var words = splitWords(el);
      words.forEach(function (w) { var m = document.createElement('span'); m.style.display = 'inline-block'; m.style.overflow = 'hidden'; m.style.verticalAlign = 'top'; m.style.paddingBottom = '.08em'; w.parentNode.insertBefore(m, w); m.appendChild(w); w.style.display = 'inline-block'; });
      gsap.from(words, { yPercent: 110, duration: 1.3, ease: 'expo.out', stagger: .05, scrollTrigger: { trigger: el, start: 'top 85%', once: true } });
    });
  }

  /* ---------------------------------------------------------------
     7. Manifesto ink-fill
     --------------------------------------------------------------- */
  function inkFill() {
    $$('.manifesto__text').forEach(function (el) {
      var words = splitWords(el);
      if (!motion || !words.length) return;   // no JS / reduced motion => solid forest text
      // Only now do we hand the words over to the gradient treatment, so the
      // statement is fully legible if this script never runs.
      el.classList.add('is-ready');
      // Each word carries a hard-edged gradient 230% of its own width. Sweeping the
      // background from 100% to 0% drags that edge across the glyphs — a real ink
      // front rather than a colour switch. Words overlap so the line flows.
      gsap.timeline({
        scrollTrigger: { trigger: el, start: 'top 80%', end: 'bottom 55%', scrub: .55 }
      }).fromTo(words,
        { backgroundPosition: '100% 0' },
        { backgroundPosition: '0% 0', ease: 'none', duration: 3, stagger: { amount: 10 } }
      );
    });
  }

  /* ---------------------------------------------------------------
     8. Grain Journey — pinned horizontal + self-drawing swoosh
     --------------------------------------------------------------- */
  function journey() {
    var wrap = $('.journey__pin'), track = $('.journey__track');
    if (!wrap || !track || !motion) return;
    var mm = gsap.matchMedia();
    mm.add('(min-width: 901px)', function () {
      var path = $('.journey__swoosh path'), len = path ? path.getTotalLength() : 0;
      if (path) gsap.set(path, { strokeDasharray: len, strokeDashoffset: len });
      var dist = function () { return track.scrollWidth - window.innerWidth; };
      var tl = gsap.timeline({ scrollTrigger: { trigger: wrap, start: 'top top', end: function () { return '+=' + dist(); }, pin: true, scrub: 1, invalidateOnRefresh: true, anticipatePin: 1 } });
      tl.to(track, { x: function () { return -dist(); }, ease: 'none' }, 0);
      if (path) tl.to(path, { strokeDashoffset: 0, ease: 'none' }, 0);
      tl.to('.journey__progress i', { scaleX: 1, ease: 'none' }, 0);
      $$('.journey__panel').forEach(function (p) {
        gsap.from(p.querySelectorAll('.journey__visual svg, .journey__visual img'), { scale: .8, autoAlpha: 0, ease: 'none', scrollTrigger: { trigger: p, containerAnimation: tl, start: 'left 90%', end: 'left 40%', scrub: true } });
      });
    });
  }

  /* ---------------------------------------------------------------
     9. Counters
     --------------------------------------------------------------- */
  function counters() {
    $$('[data-count]').forEach(function (el) {
      var to = parseFloat(el.dataset.count), suffix = el.dataset.suffix || '';
      if (!motion) { el.textContent = to.toLocaleString('en-IN') + suffix; return; }
      var o = { v: 0 };
      gsap.to(o, { v: to, duration: 2.2, ease: 'power3.out', scrollTrigger: { trigger: el, start: 'top 85%', once: true }, onUpdate: function () { el.textContent = Math.round(o.v).toLocaleString('en-IN') + suffix; } });
    });
  }

  /* ---------------------------------------------------------------
     10. Brand feature — cream → gold glow, floating logo
     --------------------------------------------------------------- */
  function brandFeature() {
    var s = $('.brand-feature'); if (!s || !motion) return;
    gsap.to('.brand-feature__bg', { opacity: 1, ease: 'none', scrollTrigger: { trigger: s, start: 'top 70%', end: 'center center', scrub: true } });
    gsap.fromTo('.brand-feature__logo', { y: 80, rotate: -4 }, { y: -40, rotate: 2, ease: 'none', scrollTrigger: { trigger: s, start: 'top bottom', end: 'bottom top', scrub: true } });
    gsap.to('.brand-feature__ring', { rotate: 120, ease: 'none', scrollTrigger: { trigger: s, start: 'top bottom', end: 'bottom top', scrub: true } });
  }

  /* ---------------------------------------------------------------
     9b. Trade map — a real world, drawn in dots

     The old map was an 18px dot grid across the whole frame, which is why it
     read as graph paper rather than a world, and the markets were hard-coded
     SVG coordinates that happened to look roughly right. Both are gone.

     Land is described as latitude/longitude boxes and projected the same way
     the markers are, so a dot and a marker can never disagree. Markets arrive
     as JSON on the container (from the gor_market taxonomy in WordPress), so
     adding a market never means touching this file.
     --------------------------------------------------------------- */
  var GOR_MAP = {
    W: 1000, LAT_TOP: 76, LAT_BOT: -52, STEP: 4,
    /* Coarse land, as [latMin, latMax, lngMin, lngMax]. Accurate enough to be
       read as continents at dot scale; small seas are below this resolution. */
    land: [
      [55,71,-168,-141],[52,70,-141,-102],[50,68,-102,-62],[30,49,-124,-71],
      [25,31,-87,-80],[16,30,-112,-88],[23,32,-116,-109],[8,18,-92,-77],
      [60,76,-52,-22],
      [0,12,-79,-60],[1,8,-60,-51],[-14,2,-74,-44],[-16,-2,-48,-35],
      [-30,-14,-58,-39],[-25,-10,-70,-54],[-55,-18,-75,-68],[-52,-22,-70,-58],
      [36,44,-10,3],[43,51,-5,8],[50,59,-8,2],[51,55,-11,-6],[55,71,5,31],
      [45,55,5,25],[37,47,7,18],[36,48,14,29],[44,60,25,40],
      [20,33,-17,11],[15,31,11,36],[4,20,-17,15],[-6,15,8,31],[2,18,32,51],
      [-12,5,29,42],[-35,-12,12,38],[-26,-12,43,50],
      [36,42,26,45],[12,37,34,60],[25,40,44,63],[36,50,50,88],[50,70,28,60],
      [50,73,60,140],[43,66,130,180],[35,53,75,135],[20,36,98,123],
      [8,35,68,90],[6,10,79,82],[9,28,92,109],[-9,7,95,141],[6,19,118,127],
      [31,46,129,146],[34,43,124,131],[-11,-1,131,151],
      [-39,-11,113,154],[-44,-40,144,149],[-47,-34,166,179]
    ],
    /* Water inside a land box, same shape. Only bodies big enough to read. */
    sea: [ [52,64,-96,-78], [41,48,28,41], [37,47,47,55] ]
  };

  function gorMapH() {
    // x spans 360 degrees across W units, so the scale is W/360 units per
    // degree. y has to use the SAME scale or the world stretches vertically —
    // which is what a stray x2 here was doing, and why the continents read as
    // blocks and the routes bowed the wrong way.
    return (GOR_MAP.LAT_TOP - GOR_MAP.LAT_BOT) / 360 * GOR_MAP.W;
  }
  function gorProject(lat, lng) {
    return [
      (lng + 180) / 360 * GOR_MAP.W,
      (GOR_MAP.LAT_TOP - lat) / (GOR_MAP.LAT_TOP - GOR_MAP.LAT_BOT) * gorMapH()
    ];
  }
  function gorIsLand(lat, lng) {
    var i, b;
    for (i = 0; i < GOR_MAP.sea.length; i++) {
      b = GOR_MAP.sea[i];
      if (lat >= b[0] && lat <= b[1] && lng >= b[2] && lng <= b[3]) return false;
    }
    for (i = 0; i < GOR_MAP.land.length; i++) {
      b = GOR_MAP.land[i];
      if (lat >= b[0] && lat <= b[1] && lng >= b[2] && lng <= b[3]) return true;
    }
    return false;
  }

  /* One <path> of tiny squares rather than ~900 <circle> nodes: same look at
     this size, a single element for the browser to deal with. */
  function gorLandPath() {
    var d = '', r = 1.5, s = GOR_MAP.STEP, lat, lng, p;
    for (lat = GOR_MAP.LAT_TOP; lat > GOR_MAP.LAT_BOT; lat -= s) {
      for (lng = -180; lng < 180; lng += s) {
        if (!gorIsLand(lat - s / 2, lng + s / 2)) continue;
        p = gorProject(lat - s / 2, lng + s / 2);
        d += 'M' + (p[0] - r).toFixed(1) + ' ' + (p[1] - r).toFixed(1) +
             'h' + (r * 2) + 'v' + (r * 2) + 'h-' + (r * 2) + 'z';
      }
    }
    return d;
  }

  function tradeMap() {
    var wrap = $('.trade__map');
    if (!wrap) return;
    var svg = wrap.querySelector('svg');
    if (!svg) return;

    var origin, markets;
    try {
      origin  = JSON.parse(wrap.getAttribute('data-origin') || 'null');
      markets = JSON.parse(wrap.getAttribute('data-markets') || '[]');
    } catch (e) { return; }
    if (!origin || !markets.length) return;

    var ns = 'http://www.w3.org/2000/svg';
    var H  = gorMapH();
    svg.setAttribute('viewBox', '0 0 ' + GOR_MAP.W + ' ' + H.toFixed(0));

    function el(name, attrs) {
      var n = document.createElementNS(ns, name), k;
      for (k in attrs) { if (attrs.hasOwnProperty(k)) n.setAttribute(k, attrs[k]); }
      return n;
    }

    // --- land -------------------------------------------------------------
    var gLand = el('g', { 'class': 'trade__land' });
    gLand.appendChild(el('path', { d: gorLandPath() }));
    svg.appendChild(gLand);

    // --- routes and destinations -----------------------------------------
    var o = gorProject(origin.lat, origin.lng);
    var gRoutes = el('g', { 'class': 'trade__routes' });
    var gNodes  = el('g', { 'class': 'trade__nodes' });
    var labels  = [];

    markets.forEach(function (m) {
      var p  = gorProject(m.lat, m.lng);
      var dx = p[0] - o[0], dy = p[1] - o[1];
      var dist = Math.sqrt(dx * dx + dy * dy);
      // Every lane bows towards the pole, more so the longer it runs — which
      // is roughly what a great circle does on this projection.
      var bow = Math.min(0.24, 0.10 + dist / 2400);
      var cx  = (o[0] + p[0]) / 2;
      var cy  = (o[1] + p[1]) / 2 - dist * bow;

      gRoutes.appendChild(el('path', {
        'class': 'trade__route',
        d: 'M' + o[0].toFixed(1) + ' ' + o[1].toFixed(1) +
           ' Q' + cx.toFixed(1) + ' ' + cy.toFixed(1) +
           ' ' + p[0].toFixed(1) + ' ' + p[1].toFixed(1)
      }));

      // Labels start away from the origin so they never cross their own route.
      // Where that puts two of them on top of each other, placeLabels() below
      // moves the later one.
      var left = p[0] < o[0];
      var g = el('g', {
        /* The status is whatever the data says — target, potential, or active
           once trade has commenced. Hard-coding one of them here is what made
           'entering' the only state the map could draw. */
        'class': 'trade__node' + (m.status ? ' is-' + m.status : ''),
        'data-market': m.slug || ''
      });
      g.appendChild(el('circle', { 'class': 'trade__halo', cx: p[0].toFixed(1), cy: p[1].toFixed(1), r: 9 }));
      g.appendChild(el('circle', { 'class': 'trade__dot',  cx: p[0].toFixed(1), cy: p[1].toFixed(1), r: 3.4 }));
      var t = el('text', {
        x: (p[0] + (left ? -12 : 12)).toFixed(1),
        y: (p[1] + 4.5).toFixed(1),
        'text-anchor': left ? 'end' : 'start'
      });
      t.textContent = m.name;
      g.appendChild(t);
      gNodes.appendChild(g);
      labels.push({ text: t, p: p, left: left });
    });

    // --- origin -----------------------------------------------------------
    var gO = el('g', { 'class': 'trade__node trade__origin' });
    gO.appendChild(el('circle', { 'class': 'trade__pulse', cx: o[0].toFixed(1), cy: o[1].toFixed(1), r: 7 }));
    gO.appendChild(el('circle', { 'class': 'trade__halo',  cx: o[0].toFixed(1), cy: o[1].toFixed(1), r: 13 }));
    gO.appendChild(el('circle', { 'class': 'trade__dot',   cx: o[0].toFixed(1), cy: o[1].toFixed(1), r: 5 }));
    var to = el('text', { x: (o[0] + 14).toFixed(1), y: (o[1] + 18).toFixed(1) });
    to.textContent = origin.name;
    gO.appendChild(to);
    gNodes.appendChild(gO);

    svg.appendChild(gRoutes);
    svg.appendChild(gNodes);

    /* --- keep the labels off each other ---------------------------------
       London and Rotterdam are 4.6 degrees apart, which on a 1000-unit world
       is twelve units — so "United Kingdom" and "Europe" were printed on top
       of one another, and "Saudi Arabia" and "UAE" very nearly were. Moving
       the markets would be a lie about where they are, so instead each label
       takes the first position that is actually clear: beside its own dot as
       before, then above or below it, then on its other side. The first
       market asked keeps its natural spot, so the common case is unchanged.

       This runs after the SVG is in the document, because the width of a
       label is the width the font gives it, not a guess from its length. */
    (function placeLabels() {
      if (!labels.length) return;
      var PAD = 3, ABOVE = 10, BELOW = 4;   // the text box around its baseline
      var dots = [], taken = [];

      labels.forEach(function (L, i) {
        dots.push({ x1: L.p[0] - 7, x2: L.p[0] + 7, y1: L.p[1] - 7, y2: L.p[1] + 7, own: i });
      });
      dots.push({ x1: o[0] - 10, x2: o[0] + 10, y1: o[1] - 10, y2: o[1] + 10, own: -1 });

      function boxFor(L, c, w) {
        var x = L.p[0] + c.dx, y = L.p[1] + c.dy, x1;
        if (c.anchor === 'end') { x1 = x - w; }
        else if (c.anchor === 'middle') { x1 = x - w / 2; }
        else { x1 = x; }
        return { x1: x1 - PAD, x2: x1 + w + PAD, y1: y - ABOVE - PAD, y2: y + BELOW + PAD };
      }
      function overlaps(a, b) {
        return a.x1 < b.x2 && a.x2 > b.x1 && a.y1 < b.y2 && a.y2 > b.y1;
      }
      function clear(b, self) {
        if (b.x1 < 2 || b.x2 > GOR_MAP.W - 2 || b.y1 < 2 || b.y2 > H - 2) return false;
        var i;
        for (i = 0; i < taken.length; i++) { if (overlaps(b, taken[i])) return false; }
        for (i = 0; i < dots.length; i++) {
          if (dots[i].own !== self && overlaps(b, dots[i])) return false;
        }
        return true;
      }

      labels.forEach(function (L, i) {
        var w = 0;
        try { w = L.text.getComputedTextLength(); } catch (e) { w = 0; }
        if (!w) { w = L.text.textContent.length * 7.4; }

        var near = L.left ? 'end' : 'start';
        var far  = L.left ? 'start' : 'end';
        var nx   = L.left ? -12 : 12;
        // The vertical steps are larger than the box is tall (20 units), or a
        // "lifted" label would still sit on the one it was moving away from.
        var cands = [
          { dx: nx,  dy: 4.5, anchor: near },    // where it was
          { dx: nx,  dy: -19, anchor: near },    // same side, lifted
          { dx: nx,  dy: 28,  anchor: near },    // same side, dropped
          { dx: -nx, dy: 4.5, anchor: far },     // the other side
          { dx: -nx, dy: -19, anchor: far },
          { dx: -nx, dy: 28,  anchor: far },
          { dx: 0,   dy: -22, anchor: 'middle' },
          { dx: 0,   dy: 31,  anchor: 'middle' }
        ];

        var chosen = cands[0], box = boxFor(L, cands[0], w), j, b;
        for (j = 0; j < cands.length; j++) {
          b = boxFor(L, cands[j], w);
          if (clear(b, i)) { chosen = cands[j]; box = b; break; }
        }
        L.text.setAttribute('x', (L.p[0] + chosen.dx).toFixed(1));
        L.text.setAttribute('y', (L.p[1] + chosen.dy).toFixed(1));
        L.text.setAttribute('text-anchor', chosen.anchor);
        taken.push(box);
      });
    })();

    wrap.classList.add('is-drawn');

    // --- motion -----------------------------------------------------------
    var routes = $$('.trade__route', svg);
    routes.forEach(function (r) {
      var len = r.getTotalLength();
      r.style.strokeDasharray = len;
      r.style.strokeDashoffset = motion ? len : 0;
    });
    if (!motion || typeof gsap === 'undefined') return;

    var tl = gsap.timeline({ scrollTrigger: { trigger: wrap, start: 'top 78%', once: true } });
    tl.to(routes, { strokeDashoffset: 0, duration: 1.8, ease: 'power2.inOut', stagger: 0.12 })
      // fromTo, not from+autoAlpha: autoAlpha sets visibility:hidden up front,
      // and if the trigger has already passed on load the markers never come
      // back. fromTo on opacity cannot strand them.
      .fromTo($$('.trade__nodes .trade__node', svg),
        { opacity: 0 },
        { opacity: 1, duration: 0.7, ease: 'power2.out', stagger: 0.09 }, '-=1.2');

    // Hovering a market lifts it and dims the rest, on pointer devices only.
    if (window.matchMedia && window.matchMedia('(hover: hover)').matches) {
      $$('.trade__nodes .trade__node', svg).forEach(function (n) {
        n.addEventListener('mouseenter', function () { wrap.classList.add('is-focused'); n.classList.add('is-active'); });
        n.addEventListener('mouseleave', function () { wrap.classList.remove('is-focused'); n.classList.remove('is-active'); });
      });
    }
  }

  /* ---------------------------------------------------------------
     12. Footer wordmark rise
     --------------------------------------------------------------- */
  function footerWord() {
    var el = $('.footer-word'); if (!el || !motion) return;
    var txt = el.textContent.trim(); el.setAttribute('aria-label', txt); el.innerHTML = '';
    txt.split('').forEach(function (ch) { var s = document.createElement('span'); s.setAttribute('aria-hidden', 'true'); s.textContent = ch === ' ' ? ' ' : ch; el.appendChild(s); });
    gsap.from(el.children, { yPercent: 100, duration: 1.4, ease: 'expo.out', stagger: .035, scrollTrigger: { trigger: el, start: 'top 98%', once: true } });
  }

  /* ---------------------------------------------------------------
     13. Magnetic buttons + custom cursor (desktop only)
     --------------------------------------------------------------- */
  function magnetic() {
    if (!motion || !finePointer) return;
    $$('[data-magnetic]').forEach(function (b) {
      var xTo = gsap.quickTo(b, 'x', { duration: .6, ease: 'power3' }), yTo = gsap.quickTo(b, 'y', { duration: .6, ease: 'power3' });
      b.addEventListener('mousemove', function (e) { var r = b.getBoundingClientRect(); xTo((e.clientX - r.left - r.width / 2) * .3); yTo((e.clientY - r.top - r.height / 2) * .35); });
      b.addEventListener('mouseleave', function () { xTo(0); yTo(0); });
    });
    var cur = $('.cursor'); if (!cur) return;
    var cx = gsap.quickTo(cur, 'x', { duration: .45, ease: 'power3' }), cy = gsap.quickTo(cur, 'y', { duration: .45, ease: 'power3' });
    window.addEventListener('mousemove', function (e) { cx(e.clientX); cy(e.clientY); });
    $$('[data-cursor]').forEach(function (el) {
      el.addEventListener('mouseenter', function () { cur.classList.add('is-hover'); cur.querySelector('span').textContent = el.dataset.cursor; });
      el.addEventListener('mouseleave', function () { cur.classList.remove('is-hover'); });
    });
  }

  /* ---------------------------------------------------------------
     14. Page transitions (curtain) for same-origin links
     --------------------------------------------------------------- */
  function transitions() {
    if (!motion || 'onpagereveal' in window) return; // native View Transitions handle it where supported
    var curtain = $('.curtain'); if (!curtain) return;
    curtain.style.visibility = 'visible';
    gsap.fromTo(curtain, { yPercent: 0 }, { yPercent: -100, duration: 1, ease: 'expo.inOut', delay: .05 });
    document.addEventListener('click', function (e) {
      var a = e.target.closest('a'); if (!a) return;
      var href = a.getAttribute('href') || '';
      if (a.target === '_blank' || e.metaKey || e.ctrlKey || a.hasAttribute('download') || href.charAt(0) === '#' || a.host !== location.host || /add-to-cart|wp-admin|\.(pdf|zip)$/i.test(href) || a.closest('.woocommerce-MyAccount-navigation, .mini-cart')) return;
      e.preventDefault();
      gsap.fromTo(curtain, { yPercent: 100 }, { yPercent: 0, duration: .7, ease: 'expo.inOut', onComplete: function () { location.href = a.href; } });
    });
    window.addEventListener('pageshow', function (e) { if (e.persisted) gsap.set(curtain, { yPercent: -100 }); });
  }

  /* ---------------------------------------------------------------
     15. Enquiry hub & multi-step RFQ
     --------------------------------------------------------------- */
  function enquiry() {
    var hub = $('[data-enquiry-hub]'); if (!hub) return;
    var hidden = $('input[name="enquiry_type"]', hub), title = $('[data-enquiry-title]', hub);
    var params = new URLSearchParams(location.search);
    function show(type) {
      if (hidden) hidden.value = type;
      $$('[data-only]', hub).forEach(function (el) { var on = el.dataset.only.split(' ').indexOf(type) > -1; el.hidden = !on; $$('input,select,textarea', el).forEach(function (f) { f.disabled = !on; }); });
      var lab = $('input[name="type_pick"][value="' + type + '"]', hub);
      if (lab) { lab.checked = true; if (title) title.textContent = lab.dataset.label || ''; }
    }
    $$('input[name="type_pick"]', hub).forEach(function (r) { r.addEventListener('change', function () { show(r.value); }); });
    show(params.get('type') || (hidden && hidden.value) || 'general');
    var prod = params.get('product'); var pf = $('[name="product"]', hub); if (prod && pf) pf.value = prod;

    // multi-step
    $$('[data-steps]', hub).forEach(function (form) {
      var panels = $$('.step-panel', form), bars = $$('.form-steps span', form), i = 0;
      if (panels.length < 2) return;
      function go(n) {
        i = Math.max(0, Math.min(panels.length - 1, n));
        panels.forEach(function (p, k) { p.hidden = k !== i; });
        bars.forEach(function (b, k) { b.classList.toggle('is-done', k <= i); });
        var last = i === panels.length - 1;
        $$('[data-prev]', form).forEach(function (b) { b.hidden = i === 0; });
        $$('[data-next]', form).forEach(function (b) { b.hidden = last; });
        $$('[type="submit"]', form).forEach(function (b) { b.hidden = !last; });
        if (motion) gsap.from(panels[i], { autoAlpha: 0, x: 20, duration: .6, ease: 'expo.out' });
      }
      form.addEventListener('click', function (e) {
        if (e.target.closest('[data-next]')) { e.preventDefault(); var ok = $$('input,select,textarea', panels[i]).every(function (f) { return f.disabled || f.reportValidity(); }); if (ok) go(i + 1); }
        if (e.target.closest('[data-prev]')) { e.preventDefault(); go(i - 1); }
      });
      go(0);
    });
  }

  /* ---------------------------------------------------------------
     16. Mini cart (WooCommerce fires jQuery 'added_to_cart')
     --------------------------------------------------------------- */
  var cart = $('.mini-cart'), scrim = $('.mini-cart-scrim');
  function openCart() { if (!cart) return; cart.classList.add('is-open'); scrim && scrim.classList.add('is-open'); if (lenis) lenis.stop(); }
  function closeCart() { if (!cart) return; cart.classList.remove('is-open'); scrim && scrim.classList.remove('is-open'); if (lenis) lenis.start(); }
  $$('[data-cart-open]').forEach(function (b) { b.addEventListener('click', function (e) { if (cart) { e.preventDefault(); openCart(); } }); });
  $$('[data-cart-close]').forEach(function (b) { b.addEventListener('click', closeCart); });
  if (window.jQuery) window.jQuery(document.body).on('added_to_cart', function (e, frags, hash, btn) {
    openCart();
    if (motion && btn && btn[0]) burst(btn[0]);
  });

  /* grain burst on add-to-cart */
  function burst(el) {
    var r = el.getBoundingClientRect(), cols = ['#e39a22', '#c68d4f', '#8db300', '#631c0a'];
    for (var i = 0; i < 22; i++) {
      var d = document.createElement('i');
      d.style.cssText = 'position:fixed;z-index:120;pointer-events:none;width:7px;height:9px;border-radius:50%;left:' + (r.left + r.width / 2) + 'px;top:' + (r.top + r.height / 2) + 'px;background:' + cols[i % 4];
      document.body.appendChild(d);
      gsap.to(d, { x: (Math.random() - .5) * 220, y: (Math.random() - .9) * 180, rotate: Math.random() * 360, autoAlpha: 0, duration: 1 + Math.random() * .6, ease: 'expo.out', onComplete: d.remove.bind(d) });
    }
  }

  /* ---------------------------------------------------------------
     17. Anchor links through Lenis
     --------------------------------------------------------------- */
  $$('a[href^="#"]').forEach(function (a) {
    a.addEventListener('click', function (e) {
      var id = a.getAttribute('href'); if (id.length < 2) return; var t = $(id); if (!t) return;
      e.preventDefault(); setMenu(false);
      if (lenis) lenis.scrollTo(t, { offset: -70 }); else t.scrollIntoView({ behavior: reduce ? 'auto' : 'smooth' });
    });
  });

  /* ---------------------------------------------------------------
     Site search

     The header's search icon used to point at index.html, so clicking it did
     nothing. In WordPress it hands off to /?s=; here there is no server, so
     the panel searches a small index of the pages themselves. Either way the
     control now does what its label says.
     --------------------------------------------------------------- */
  function search() {
    var panel = $('#search');
    var open  = $('[data-search-open]');
    if (!panel || !open) return;

    var input   = $('#site-search', panel);
    var results = $('.search-panel__results', panel);
    var close   = $('[data-search-close]', panel);
    var index   = [];

    try { index = JSON.parse(panel.getAttribute('data-index') || '[]'); } catch (e) { index = []; }

    function setOpen(on) {
      document.body.classList.toggle('search-open', on);
      panel.setAttribute('aria-hidden', on ? 'false' : 'true');
      if (on) { panel.removeAttribute('inert'); if (input) { input.focus(); input.select(); } }
      else { panel.setAttribute('inert', ''); open.focus(); }
    }

    function render(q) {
      if (!results) return;
      results.innerHTML = '';
      q = (q || '').trim().toLowerCase();
      if (q.length < 2) { results.removeAttribute('data-state'); return; }

      var hits = index.filter(function (p) {
        return (p.t + ' ' + (p.k || '')).toLowerCase().indexOf(q) !== -1;
      }).slice(0, 8);

      if (!hits.length) {
        results.setAttribute('data-state', 'empty');
        var li = document.createElement('li');
        li.className = 'search-panel__empty';
        li.textContent = 'Nothing matches that yet.';
        results.appendChild(li);
        return;
      }
      results.setAttribute('data-state', 'has');
      hits.forEach(function (p) {
        var li = document.createElement('li');
        var a  = document.createElement('a');
        a.href = p.u;
        a.innerHTML = '<strong></strong><span></span>';
        a.querySelector('strong').textContent = p.t;
        a.querySelector('span').textContent = p.d || '';
        li.appendChild(a);
        results.appendChild(li);
      });
    }

    open.addEventListener('click', function (e) { e.preventDefault(); setOpen(true); });
    if (close) close.addEventListener('click', function () { setOpen(false); });
    panel.addEventListener('click', function (e) { if (e.target === panel) setOpen(false); });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && document.body.classList.contains('search-open')) setOpen(false);
    });
    if (input) {
      input.addEventListener('input', function () { render(input.value); });
      // Enter goes to the best match rather than reloading the page.
      var form = input.form;
      if (form) {
        form.addEventListener('submit', function (e) {
          var first = results && results.querySelector('a');
          if (first) { e.preventDefault(); window.location.href = first.getAttribute('href'); }
        });
      }
    }
  }

  /* ---------------------------------------------------------------
     Boot
     --------------------------------------------------------------- */
  /* Each step is isolated. [data-reveal] starts at opacity 0 under .js-motion,
     so one throwing function used to leave whole sections permanently blank —
     and because __gorReady is set at the top of this file, the inline 3.5s
     fallback had already been disarmed. Now a failure anywhere drops
     .js-motion, which restores every hidden element immediately. */
  function step(name, fn) {
    try {
      fn();
    } catch (err) {
      root.classList.remove('js-motion');
      if (window.console && console.warn) {
        console.warn('[gor] ' + name + ' failed; motion disabled so content stays visible.', err);
      }
    }
  }

  function boot() {
    step('grainCanvas', grainCanvas);
    if (hasGSAP) {
      step('heroIntro', heroIntro);
      step('reveals', reveals);
      step('inkFill', inkFill);
      step('journey', journey);
      step('counters', counters);
      step('brandFeature', brandFeature);
      step('footerWord', footerWord);
      step('magnetic', magnetic);
      step('transitions', transitions);
    } else {
      step('inkFill', inkFill);
      step('counters', counters);
    }
    step('tradeMap', tradeMap);
    step('enquiry', enquiry);
    step('search', search);
    // Only now is it safe to disarm the inline fallback: everything that could
    // have left content hidden has already run.
    window.__gorReady = true;
    if (hasGSAP) {
      window.addEventListener('load', function () { ScrollTrigger.refresh(); });
      if (document.fonts && document.fonts.ready) document.fonts.ready.then(function () { ScrollTrigger.refresh(); });
    }
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot); else boot();
})();
