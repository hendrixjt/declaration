(() => {
  'use strict';

  const root = document.documentElement;
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const header = document.querySelector('[data-header]');
  const menuToggle = document.querySelector('[data-menu-toggle]');
  const menu = document.querySelector('[data-menu]');
  const revealItems = [...document.querySelectorAll('[data-reveal]')];
  const parallaxItems = [...document.querySelectorAll('[data-parallax]')];

  root.classList.add('o2-ready');

  const closeMenu = () => {
    root.classList.remove('o2-menu-open');
    menuToggle?.setAttribute('aria-expanded', 'false');
    menuToggle?.setAttribute('aria-label', 'Open navigation');
  };

  menuToggle?.addEventListener('click', () => {
    const isOpen = !root.classList.contains('o2-menu-open');
    root.classList.toggle('o2-menu-open', isOpen);
    menuToggle.setAttribute('aria-expanded', String(isOpen));
    menuToggle.setAttribute('aria-label', isOpen ? 'Close navigation' : 'Open navigation');
  });

  menu?.querySelectorAll('a').forEach((link) => link.addEventListener('click', closeMenu));

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') closeMenu();
  });

  const revealObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;
      entry.target.classList.add('is-visible');
      observer.unobserve(entry.target);
    });
  }, { threshold: 0.14, rootMargin: '0px 0px -8% 0px' });

  revealItems.forEach((item) => revealObserver.observe(item));

  let ticking = false;
  const updateMotion = () => {
    const scrollY = window.scrollY;
    header?.classList.toggle('is-scrolled', scrollY > 30);

    if (!reducedMotion && window.innerWidth > 760) {
      const center = window.innerHeight / 2;
      parallaxItems.forEach((item) => {
        const rect = item.getBoundingClientRect();
        const speed = Number.parseFloat(item.dataset.parallax || '0');
        const shift = (rect.top + rect.height / 2 - center) * speed;
        item.style.setProperty('--parallax-shift', `${shift.toFixed(2)}px`);
      });
    }

    ticking = false;
  };

  const requestMotion = () => {
    if (ticking) return;
    ticking = true;
    window.requestAnimationFrame(updateMotion);
  };

  updateMotion();
  window.addEventListener('scroll', requestMotion, { passive: true });
  window.addEventListener('resize', requestMotion);
})();
