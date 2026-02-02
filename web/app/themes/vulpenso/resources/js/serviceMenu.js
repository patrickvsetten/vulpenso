/**
 * Service Menu - Full-width dropdown (Desktop only)
 * Opens a full-width panel below the navigation with service grid items
 */
export function initServiceMenu() {
  // Only initialize on desktop (lg breakpoint and above)
  if (window.innerWidth < 1024) return;

  const nav = document.querySelector('[data-service-menu]');
  const trigger = document.querySelector('[data-service-menu-toggle]');
  const dropdown = document.querySelector('[data-service-dropdown]');
  const overlay = document.querySelector('[data-service-menu-overlay]');

  if (!nav || !trigger || !dropdown) return;

  const isActive = () => nav.getAttribute('data-service-menu-status') === 'active';

  const openMenu = () => {
    nav.setAttribute('data-service-menu-status', 'active');
    dropdown.classList.add('is-active');
    trigger.setAttribute('aria-expanded', 'true');
    if (overlay) overlay.classList.add('is-active');
  };

  const closeMenu = () => {
    nav.setAttribute('data-service-menu-status', 'not-active');
    dropdown.classList.remove('is-active');
    trigger.setAttribute('aria-expanded', 'false');
    if (overlay) overlay.classList.remove('is-active');
  };

  // Toggle on click
  trigger.addEventListener('click', (e) => {
    e.preventDefault();
    e.stopPropagation();

    if (isActive()) {
      closeMenu();
    } else {
      openMenu();
    }
  });

  // Close on click outside
  document.addEventListener('click', (e) => {
    if (isActive() && !nav.contains(e.target) && !dropdown.contains(e.target)) {
      closeMenu();
    }
  });

  // Close on ESC key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && isActive()) {
      closeMenu();
      trigger.focus();
    }
  });

  // Close on overlay click
  if (overlay) {
    overlay.addEventListener('click', closeMenu);
  }

  // Close menu when clicking a link inside dropdown
  dropdown.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
      closeMenu();
    });
  });

  // Handle resize - close menu if resizing below desktop breakpoint
  let resizeTimeout;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
      if (window.innerWidth < 1024 && isActive()) {
        closeMenu();
      }
    }, 100);
  });

  // Keyboard navigation within dropdown
  dropdown.addEventListener('keydown', (e) => {
    const items = Array.from(dropdown.querySelectorAll('a'));
    const currentIndex = items.indexOf(document.activeElement);

    if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
      e.preventDefault();
      const nextIndex = (currentIndex + 1) % items.length;
      items[nextIndex]?.focus();
    } else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
      e.preventDefault();
      const prevIndex = (currentIndex - 1 + items.length) % items.length;
      items[prevIndex]?.focus();
    } else if (e.key === 'Tab' && !dropdown.contains(e.relatedTarget)) {
      closeMenu();
    }
  });

  // Focus first item when opening via keyboard
  trigger.addEventListener('keydown', (e) => {
    if ((e.key === 'Enter' || e.key === ' ') && !isActive()) {
      e.preventDefault();
      openMenu();
      setTimeout(() => {
        const firstItem = dropdown.querySelector('a');
        if (firstItem) firstItem.focus();
      }, 100);
    }
  });
}
