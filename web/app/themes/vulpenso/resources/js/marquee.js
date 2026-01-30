import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger.js';

export function initMarquee() {
  document.querySelectorAll('[data-marquee]').forEach((marquee) => {
    const marqueeContent = marquee.querySelector('[data-marquee-collection]');
    const marqueeScroll = marquee.querySelector('[data-marquee-scroll]');

    if (!marqueeContent || !marqueeScroll) return;

    // Get data attributes
    const speed = parseFloat(marquee.dataset.marqueeSpeed) || 20;
    const direction = marquee.dataset.marqueeDirection === 'right' ? 1 : -1;
    const duplicateAmount = parseInt(marquee.dataset.marqueeDuplicate) || 2;
    const scrollSpeed = parseFloat(marquee.dataset.marqueeScrollSpeed) || 10;

    // Speed multiplier based on screen width
    const speedMultiplier = window.innerWidth < 479 ? 0.25 : window.innerWidth < 991 ? 0.5 : 1;
    const marqueeSpeed = speed * (marqueeContent.offsetWidth / window.innerWidth) * speedMultiplier;

    // Set scroll container styles
    marqueeScroll.style.marginLeft = `${scrollSpeed * -1}%`;
    marqueeScroll.style.width = `${(scrollSpeed * 2) + 100}%`;

    // Duplicate marquee content
    if (duplicateAmount > 0) {
      const fragment = document.createDocumentFragment();
      for (let i = 0; i < duplicateAmount; i++) {
        fragment.appendChild(marqueeContent.cloneNode(true));
      }
      marqueeScroll.appendChild(fragment);
    }

    // Get all collection items for animation
    const marqueeItems = marquee.querySelectorAll('[data-marquee-collection]');

    // GSAP animation for marquee content
    const animation = gsap.to(marqueeItems, {
      xPercent: -100,
      repeat: -1,
      duration: marqueeSpeed,
      ease: 'linear'
    }).totalProgress(0.5);

    // Initialize marquee in the correct direction
    gsap.set(marqueeItems, { xPercent: direction === 1 ? 100 : -100 });
    animation.timeScale(direction);
    animation.play();

    // Set initial marquee status
    marquee.setAttribute('data-marquee-status', 'normal');

    // ScrollTrigger for direction inversion based on scroll
    ScrollTrigger.create({
      trigger: marquee,
      start: 'top bottom',
      end: 'bottom top',
      onUpdate: (self) => {
        const isInverted = self.direction === 1; // Scrolling down
        const currentDirection = isInverted ? -direction : direction;

        // Update animation direction and marquee status
        animation.timeScale(currentDirection);
        marquee.setAttribute('data-marquee-status', isInverted ? 'normal' : 'inverted');
      }
    });

    // Extra speed effect on scroll (parallax-like)
    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: marquee,
        start: '0% 100%',
        end: '100% 0%',
        scrub: 0
      }
    });

    const scrollStart = direction === -1 ? scrollSpeed : -scrollSpeed;
    const scrollEnd = -scrollStart;

    tl.fromTo(marqueeScroll,
      { x: `${scrollStart}vw` },
      { x: `${scrollEnd}vw`, ease: 'none' }
    );
  });
}
