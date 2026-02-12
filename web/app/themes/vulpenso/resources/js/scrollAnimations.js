import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger.js';
import SplitType from 'split-type';
gsap.registerPlugin(ScrollTrigger);

import LocomotiveScroll from 'locomotive-scroll';

// Alleen smooth scroll op non-touch devices (Safari iOS heeft problemen met Lenis)
const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;

let scroll;
if (!isTouchDevice) {
  scroll = new LocomotiveScroll({
    lerp: 0.15,
    autoResize: true,
  });
  scroll.lenisInstance?.on('scroll', ScrollTrigger.update);
}

// Anchor links
document.querySelectorAll('a[href^="#"]:not([data-whatsapp-modal-link])').forEach(item => {
  item.addEventListener('click', function (e) {
    e.preventDefault();
    const offset = this.hasAttribute('data-prices-nav-link') ? -80 : 0;
    if (scroll) {
      scroll.scrollTo(this.getAttribute('href'), { offset });
    } else {
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        window.scrollTo({ top: target.offsetTop + offset, behavior: 'smooth' });
      }
    }
  });
});

export function initScrollAnimations($) {

  gsap.config({
    force3D: true,
  });

  // SCROLL REVEAL
  const initContentRevealScroll  = () => {
    const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const ctx = gsap.context(() => {

      document.querySelectorAll('[data-reveal-group]').forEach(groupEl => {
        // Config from attributes or defaults (group-level)
        const groupStaggerSec = (parseFloat(groupEl.getAttribute('data-stagger')) || 100) / 1000; // ms → sec
        const groupDistance = groupEl.getAttribute('data-distance') || '2em';
        const triggerStart = groupEl.getAttribute('data-start') || 'top 80%';

        const animDuration = 0.8;
        const animEase = "power4.inOut";

        // Reduced motion: show immediately
        if (prefersReduced) {
          gsap.set(groupEl, { clearProps: 'all', y: 0, autoAlpha: 1 });
          return;
        }

        // If no direct children, animate the group element itself
        const directChildren = Array.from(groupEl.children).filter(el => el.nodeType === 1);
        if (!directChildren.length) {
          gsap.set(groupEl, { y: groupDistance, autoAlpha: 0 });
          ScrollTrigger.create({
            trigger: groupEl,
            start: triggerStart,
            once: true,
            onEnter: () => gsap.to(groupEl, {
              y: 0,
              autoAlpha: 1,
              duration: animDuration,
              ease: animEase,
              onComplete: () => gsap.set(groupEl, { clearProps: 'all' })
            })
          });
          return;
        }

        // Build animation slots: item or nested (deep layers allowed)
        const slots = [];
        directChildren.forEach(child => {
          const nestedGroup = child.matches('[data-reveal-group-nested]')
            ? child
            : child.querySelector(':scope [data-reveal-group-nested]');

          if (nestedGroup) {
            const includeParent = child.getAttribute('data-ignore') === 'false' || nestedGroup.getAttribute('data-ignore') === 'false';
            slots.push({ type: 'nested', parentEl: child, nestedEl: nestedGroup, includeParent });
          } else {
            slots.push({ type: 'item', el: child });
          }
        });

        // Initial hidden state
        slots.forEach(slot => {
          if (slot.type === 'item') {
            // If the element itself is a nested group, force group distance (prevents it from using its own data-distance)
            const isNestedSelf = slot.el.matches('[data-reveal-group-nested]');
            const d = isNestedSelf ? groupDistance : (slot.el.getAttribute('data-distance') || groupDistance);
            gsap.set(slot.el, { y: d, autoAlpha: 0 });
          } else {
            // Parent follows the group's distance when included, regardless of nested's data-distance
            if (slot.includeParent) gsap.set(slot.parentEl, { y: groupDistance, autoAlpha: 0 });
            // Children use nested group's own distance (fallback to group distance)
            const nestedD = slot.nestedEl.getAttribute('data-distance') || groupDistance;
            Array.from(slot.nestedEl.children).forEach(target => gsap.set(target, { y: nestedD, autoAlpha: 0 }));
          }
        });

        // Extra safety: if a nested parent is included, re-assert its distance to the group's value
        slots.forEach(slot => {
          if (slot.type === 'nested' && slot.includeParent) {
            gsap.set(slot.parentEl, { y: groupDistance });
          }
        });

        // Reveal sequence
        ScrollTrigger.create({
          trigger: groupEl,
          start: triggerStart,
          once: true,
          onEnter: () => {
            const tl = gsap.timeline();

            slots.forEach((slot, slotIndex) => {
              const slotTime = slotIndex * groupStaggerSec;

              if (slot.type === 'item') {
                tl.to(slot.el, {
                  y: 0,
                  autoAlpha: 1,
                  duration: animDuration,
                  ease: animEase,
                  onComplete: () => gsap.set(slot.el, { clearProps: 'all' })
                }, slotTime);
              } else {
                // Optionally include the parent at the same slot time (parent uses group distance)
                if (slot.includeParent) {
                  tl.to(slot.parentEl, {
                    y: 0,
                    autoAlpha: 1,
                    duration: animDuration,
                    ease: animEase,
                    onComplete: () => gsap.set(slot.parentEl, { clearProps: 'all' })
                  }, slotTime);
                }
                // Nested children use nested stagger (ms → sec); fallback to group stagger
                const nestedMs = parseFloat(slot.nestedEl.getAttribute('data-stagger'));
                const nestedStaggerSec = isNaN(nestedMs) ? groupStaggerSec : nestedMs / 1000;
                Array.from(slot.nestedEl.children).forEach((nestedChild, nestedIndex) => {
                  tl.to(nestedChild, {
                    y: 0,
                    autoAlpha: 1,
                    duration: animDuration,
                    ease: animEase,
                    onComplete: () => gsap.set(nestedChild, { clearProps: 'all' })
                  }, slotTime + nestedIndex * nestedStaggerSec);
                });
              }
            });
          }
        });
      });

    });

    return () => ctx.revert();
  }

  // TEXT REVEAL (karaoke effect)
  const initTextReveal = () => {
    const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const textRevealElements = [];

    document.querySelectorAll('[data-text-reveal]').forEach(el => {
      const type = el.getAttribute('data-text-reveal') || 'chars';
      const split = new SplitType(el, { types: 'words, chars' });
      const targets = split[type];

      if (prefersReduced || !targets?.length) return;

      // Op touch devices: geen scrub (veroorzaakt scroll haperingen op Safari iOS)
      if (isTouchDevice) {
        gsap.set(targets, { opacity: 1 });
        return;
      }

      // Use GSAP timeline with scrub for better performance
      gsap.set(targets, { opacity: 0.15, willChange: 'opacity' });

      const tl = gsap.timeline();
      tl.to(targets, {
        opacity: 1,
        stagger: 0.03,
        ease: 'none',
      });

      const trigger = ScrollTrigger.create({
        trigger: el,
        start: 'top 60%',
        end: 'top 20%',
        animation: tl,
        scrub: 1,
        onComplete: () => {
          gsap.set(targets, { willChange: 'auto' });
        }
      });

      textRevealElements.push({ el, split, trigger, type });
    });

    // Debounced resize handler
    let resizeTimeout;
    window.addEventListener('resize', () => {
      clearTimeout(resizeTimeout);
      resizeTimeout = setTimeout(() => {
        textRevealElements.forEach((item) => {
          item.trigger.kill();
          item.split.revert();

          const newSplit = new SplitType(item.el, { types: 'words, chars' });
          const targets = newSplit[item.type];

          if (!targets?.length) return;

          gsap.set(targets, { opacity: 0.15, willChange: 'opacity' });

          const tl = gsap.timeline();
          tl.to(targets, {
            opacity: 1,
            stagger: 0.03,
            ease: 'none',
          });

          const newTrigger = ScrollTrigger.create({
            trigger: item.el,
            start: 'top 60%',
            end: 'top 20%',
            animation: tl,
            scrub: 1,
            onComplete: () => {
              gsap.set(targets, { willChange: 'auto' });
            }
          });

          item.split = newSplit;
          item.trigger = newTrigger;
        });
      }, 250);
    });
  };

  initContentRevealScroll();
  initTextReveal();
}
