import gsap from 'gsap';

export function initRotatingText() {
  document.querySelectorAll('[data-rotating-title]').forEach((heading) => {
    const stepDuration = parseFloat(heading.getAttribute('data-step-duration') || '1.75');
    const rotatingSpan = heading.querySelector('[data-rotating-words]');

    if (!rotatingSpan) return;

    const rawWords = rotatingSpan.getAttribute('data-rotating-words') || '';
    const words = rawWords
      .split(',')
      .map((w) => w.trim())
      .filter(Boolean);

    if (!words.length) return;

    // Build inner wrapper with stacked words
    const wrapper = document.createElement('span');
    wrapper.className = 'rotating-text__inner';

    const wordEls = words.map((word) => {
      const el = document.createElement('span');
      el.className = 'rotating-text__word';
      el.textContent = word;
      wrapper.appendChild(el);
      return el;
    });

    // Replace the original content of the highlight span
    rotatingSpan.textContent = '';
    rotatingSpan.appendChild(wrapper);

    // Wait for render
    requestAnimationFrame(() => {
      const inDuration = 0.75;
      const outDuration = 0.6;

      let activeIndex = 0;
      let wordHeight = wordEls[0].offsetHeight;

      // Function to update dimensions
      function updateDimensions() {
        wordHeight = wordEls[0].offsetHeight;
        wrapper.style.height = wordHeight + 'px';

        // Update width to current active word
        const currentWord = wordEls[activeIndex];
        wrapper.style.width = currentWord.offsetWidth + 'px';

        // Reset position of active word
        gsap.set(currentWord, { y: 0 });
      }

      // Initial setup
      updateDimensions();

      // Initial state: all words hidden below
      gsap.set(wordEls, { y: wordHeight, autoAlpha: 0 });

      // Show first word
      const firstWord = wordEls[activeIndex];
      gsap.set(firstWord, { y: 0, autoAlpha: 1 });

      // Debounced resize handler
      let resizeTimeout;
      window.addEventListener('resize', () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(updateDimensions, 100);
      });

      function showNext() {
        const nextIndex = (activeIndex + 1) % wordEls.length;
        const prev = wordEls[activeIndex];
        const current = wordEls[nextIndex];

        // Recalculate height in case of resize
        wordHeight = wordEls[0].offsetHeight;
        const targetWidth = current.offsetWidth;

        // Animate wrapper width
        gsap.to(wrapper, {
          width: targetWidth,
          duration: inDuration,
          ease: 'power4.inOut'
        });

        // Move old word out (up)
        gsap.to(prev, {
          y: -wordHeight,
          autoAlpha: 0,
          duration: outDuration,
          ease: 'power4.inOut'
        });

        // Reveal new word (from below)
        gsap.fromTo(
          current,
          { y: wordHeight, autoAlpha: 0 },
          {
            y: 0,
            autoAlpha: 1,
            duration: inDuration,
            ease: 'power4.inOut'
          }
        );

        activeIndex = nextIndex;
        gsap.delayedCall(stepDuration, showNext);
      }

      // Start rotation
      if (wordEls.length > 1) {
        gsap.delayedCall(stepDuration, showNext);
      }
    });
  });
}
