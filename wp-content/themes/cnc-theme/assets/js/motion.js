document.addEventListener('DOMContentLoaded', function () {
	var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	// Mouse-tracking 3D tilt on large photography — plain CSS transition,
	// intentionally not GSAP (cheap, runs on every mousemove).
	var tiltTargets = document.querySelectorAll('figure.wp-block-image img, .wp-block-cover img');
	tiltTargets.forEach(function (el) {
		el.classList.add('cnc-tilt');
		el.addEventListener('mousemove', function (e) {
			if (reduceMotion) return;
			var rect = el.getBoundingClientRect();
			var x = e.clientX - rect.left;
			var y = e.clientY - rect.top;
			var rotateX = ((y - rect.height / 2) / (rect.height / 2)) * -5;
			var rotateY = ((x - rect.width / 2) / (rect.width / 2)) * 5;
			el.style.transform = 'perspective(900px) rotateX(' + rotateX + 'deg) rotateY(' + rotateY + 'deg) scale(1.015)';
		});
		el.addEventListener('mouseleave', function () {
			el.style.transform = 'perspective(900px) rotateX(0) rotateY(0) scale(1)';
		});
	});

	// GSAP drives scroll choreography, organic-shape drift/parallax, and
	// magnetic buttons. If the CDN script failed to load, fail safe: content
	// is visible by default in plain HTML/CSS, so there is nothing to unhide.
	if (typeof gsap === 'undefined') return;
	gsap.registerPlugin(ScrollTrigger);

	var mm = gsap.matchMedia();

	mm.add('(prefers-reduced-motion: no-preference)', function () {
		// Section-level reveal (top-level sections/groups inside <main>).
		document.querySelectorAll('main.wp-block-group > section, main.wp-block-group > .wp-block-group').forEach(function (section) {
			gsap.from(section, {
				opacity: 0,
				y: 28,
				duration: 0.7,
				ease: 'power2.out',
				scrollTrigger: { trigger: section, start: 'top 88%', toggleActions: 'play none none reverse' }
			});
		});

		// Staggered grid/card reveal — any container marked cnc-motion-stagger
		// animates its direct children in sequence as it enters view.
		document.querySelectorAll('.cnc-motion-stagger').forEach(function (grid) {
			gsap.from(grid.children, {
				opacity: 0,
				y: 24,
				duration: 0.5,
				stagger: { each: Math.min(0.08, 0.9 / grid.children.length), from: 'start' },
				ease: 'power2.out',
				scrollTrigger: { trigger: grid, start: 'top 85%', toggleActions: 'play none none reverse' }
			});
		});

		// Botanical/fruit line-art: slow continuous drift + rotation, plus a
		// gentle scroll-parallax so the layer feels like it has depth rather
		// than being pasted on top of the hero.
		document.querySelectorAll('.organic-shape').forEach(function (shape, i) {
			var depth = parseFloat(shape.getAttribute('data-depth') || '1');
			var driftY = 12 + (i % 3) * 7;
			var spin = (i % 2 === 0 ? 1 : -1) * (5 + (i % 3) * 3);
			var dur = 6 + (i % 4) * 1.5;

			gsap.to(shape, {
				y: '+=' + driftY,
				rotation: spin,
				duration: dur,
				ease: 'sine.inOut',
				yoyo: true,
				repeat: -1,
				transformOrigin: '50% 50%'
			});

			var section = shape.closest('section, .wp-block-group');
			if (section) {
				gsap.to(shape, {
					yPercent: -16 * depth,
					ease: 'none',
					scrollTrigger: { trigger: section, start: 'top bottom', end: 'bottom top', scrub: 1 }
				});
			}
		});

		// Magnetic pull on primary CTAs — capped to 1-2 per page by design
		// (only elements explicitly opted in via .cnc-btn-magnetic).
		document.querySelectorAll('.cnc-btn-magnetic').forEach(function (el) {
			var xTo = gsap.quickTo(el, 'x', { duration: 0.4, ease: 'elastic.out(1,0.4)' });
			var yTo = gsap.quickTo(el, 'y', { duration: 0.4, ease: 'elastic.out(1,0.4)' });
			el.addEventListener('pointermove', function (e) {
				var r = el.getBoundingClientRect();
				xTo((e.clientX - r.left - r.width / 2) * 0.25);
				yTo((e.clientY - r.top - r.height / 2) * 0.25);
			});
			el.addEventListener('pointerleave', function () { xTo(0); yTo(0); });
		});
	});
});
