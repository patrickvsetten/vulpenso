export function initServiceMenu() {
  const menus = document.querySelectorAll('[data-service-menu]');
  const overlay = document.querySelector('[data-service-menu-overlay]');

  menus.forEach(menu => {
    const trigger = menu.querySelector('[data-service-menu-toggle]');
    const bg = menu.querySelector('.service-menu__bg');
    const content = menu.querySelector('.service-menu__content');
    const ul = content?.querySelector('ul');

    if (!trigger || !bg || !content) return;

    const isActive = () => menu.getAttribute('data-service-menu-status') === 'active';

    const setTriggerSize = () => {
      const { width, height } = trigger.getBoundingClientRect();
      bg.style.width = `${width}px`;
      bg.style.height = `${height}px`;
    };

    const measureExpandedSize = () => {
      // Disable transitions and force scale(1) for accurate measurement
      content.style.transition = 'none';
      content.style.transform = 'scale(1)';
      content.offsetHeight;

      const triggerRect = trigger.getBoundingClientRect();
      const contentRect = (ul || content).getBoundingClientRect();

      // Restore styles
      content.style.transition = '';
      content.style.transform = '';

      return {
        width: Math.max(triggerRect.width, contentRect.width),
        height: triggerRect.height + contentRect.height + 4
      };
    };

    const closeMenu = () => {
      menu.setAttribute('data-service-menu-status', 'not-active');
      if (overlay) overlay.classList.remove('is-active');
      setTriggerSize();

      setTimeout(() => {
        if (!isActive()) bg.classList.remove('is-animating');
      }, 600);
    };

    const openMenu = () => {
      setTriggerSize();
      bg.classList.add('is-animating');
      menu.setAttribute('data-service-menu-status', 'active');
      if (overlay) overlay.classList.add('is-active');

      const { width, height } = measureExpandedSize();

      requestAnimationFrame(() => {
        bg.style.width = `${width}px`;
        bg.style.height = `${height}px`;
      });
    };

    // Toggle on click
    trigger.addEventListener('click', (e) => {
      e.stopPropagation();

      if (isActive()) {
        closeMenu();
      } else {
        // Close other open menus first
        menus.forEach(m => {
          if (m !== menu && m.getAttribute('data-service-menu-status') === 'active') {
            m.setAttribute('data-service-menu-status', 'not-active');
            const otherBg = m.querySelector('.service-menu__bg');
            const otherTrigger = m.querySelector('[data-service-menu-toggle]');
            if (otherBg && otherTrigger) {
              const { width, height } = otherTrigger.getBoundingClientRect();
              otherBg.style.width = `${width}px`;
              otherBg.style.height = `${height}px`;
              setTimeout(() => {
                if (m.getAttribute('data-service-menu-status') === 'not-active') {
                  otherBg.classList.remove('is-animating');
                }
              }, 600);
            }
          }
        });
        openMenu();
      }
    });

    // Close on click outside
    document.addEventListener('click', (e) => {
      if (isActive() && !menu.contains(e.target)) closeMenu();
    });

    // Close on ESC
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && isActive()) closeMenu();
    });

    // Update on resize
    let resizeTimeout;
    window.addEventListener('resize', () => {
      clearTimeout(resizeTimeout);
      resizeTimeout = setTimeout(() => {
        if (isActive()) {
          const { width, height } = measureExpandedSize();
          bg.style.width = `${width}px`;
          bg.style.height = `${height}px`;
        } else {
          setTriggerSize();
        }
      }, 100);
    });

    // Set initial trigger size
    setTriggerSize();
  });

  // Close all on overlay click
  if (overlay) {
    overlay.addEventListener('click', () => {
      menus.forEach(menu => {
        menu.setAttribute('data-service-menu-status', 'not-active');
        const bg = menu.querySelector('.service-menu__bg');
        const trigger = menu.querySelector('[data-service-menu-toggle]');
        if (bg && trigger) {
          const { width, height } = trigger.getBoundingClientRect();
          bg.style.width = `${width}px`;
          bg.style.height = `${height}px`;
          setTimeout(() => {
            if (menu.getAttribute('data-service-menu-status') === 'not-active') {
              bg.classList.remove('is-animating');
            }
          }, 600);
        }
      });
      overlay.classList.remove('is-active');
    });
  }
}
