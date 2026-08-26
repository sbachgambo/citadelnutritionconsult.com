document.addEventListener('DOMContentLoaded', function () {
	var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	if (reduceMotion) return;

	// Scroll-reveal: fade/slide up each top-level section as it enters view.
	// Safety net: heavy sub-content (e.g. video embeds) can occasionally starve
	// IntersectionObserver callbacks on the main thread, so a section that
	// never gets flagged as intersecting must still reveal itself after a
	// short delay rather than stay invisible forever.
	var sections = document.querySelectorAll('main > section');
	if (sections.length) {
		sections.forEach(function (section, i) {
			section.classList.add('reveal');
			section.style.transitionDelay = (i === 0 ? 0 : Math.min(i * 60, 180)) + 'ms';
		});

		if ('IntersectionObserver' in window) {
			var observer = new IntersectionObserver(function (entries) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting) {
						entry.target.classList.add('is-visible');
						observer.unobserve(entry.target);
					}
				});
			}, { threshold: 0.12, rootMargin: '0px 0px -60px 0px' });

			sections.forEach(function (section) { observer.observe(section); });
		} else {
			sections.forEach(function (section) { section.classList.add('is-visible'); });
		}

		setTimeout(function () {
			sections.forEach(function (section) { section.classList.add('is-visible'); });
		}, 2500);
	}

	// Mouse-tracking 3D tilt on real photography.
	var tiltTargets = document.querySelectorAll('.photo-cover');
	tiltTargets.forEach(function (el) {
		el.classList.add('tilt');
		el.addEventListener('mousemove', function (e) {
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
});
