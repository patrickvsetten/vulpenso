import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger.js';

gsap.registerPlugin(ScrollTrigger);

export function initScrollableText() {
  const sections = document.querySelectorAll('[data-scrollable-text]');

  if (!sections.length) return;

  let triggers = [];

  const createTriggers = () => {
    // Kill existing triggers
    triggers.forEach(trigger => trigger.kill());
    triggers = [];

    sections.forEach((section) => {
      const background = section.querySelector('[data-scrollable-bg]');
      const titleWrapper = section.querySelector('[data-scrollable-title]');
      const stepsWrapper = section.querySelector('[data-scrollable-steps]');

      if (!background || !titleWrapper || !stepsWrapper) return;

      // Pin the background for the duration of the section
      const bgTrigger = ScrollTrigger.create({
        trigger: section,
        start: 'top top',
        end: 'bottom bottom',
        pin: background,
        pinSpacing: false,
      });

      // Pin the title while scrolling through steps (alleen op desktop)
      if (window.innerWidth >= 768) {
        const titleTrigger = ScrollTrigger.create({
          trigger: section,
          start: 'top top',
          end: 'bottom bottom',
          pin: titleWrapper,
          pinSpacing: false,
        });
        triggers.push(titleTrigger);
      }

      triggers.push(bgTrigger);
    });
  };

  createTriggers();

  // Debounced resize handler
  let resizeTimeout;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
      createTriggers();
      ScrollTrigger.refresh();
    }, 250);
  });
}
