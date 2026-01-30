export function initPricesNav() {
  const navLinks = document.querySelectorAll('[data-prices-nav-link]');
  const tables = document.querySelectorAll('[data-prices-table]');
  const menuOffset = 80; // Offset in pixels (matches scroll-mt-20 = 5rem = 80px)

  if (navLinks.length === 0 || tables.length === 0) return;

  // Set active state function
  function setActiveLink(activeId) {
    navLinks.forEach(link => {
      const isActive = link.getAttribute('href') === `#${activeId}`;
      link.classList.toggle('is-active', isActive);
    });
  }

  // On click: set active state (Lenis handles the actual scrolling)
  navLinks.forEach(link => {
    link.addEventListener('click', function() {
      const targetId = this.getAttribute('href').substring(1);
      setActiveLink(targetId);
    });
  });

  // Active state on scroll
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        setActiveLink(entry.target.id);
      }
    });
  }, {
    rootMargin: `-${menuOffset}px 0px -60% 0px`
  });

  tables.forEach(table => observer.observe(table));
}
