import Swiper from 'swiper/bundle';

export function initCTASlider() {
  const ctaSliders = document.querySelectorAll(".cta-slider");

  ctaSliders.forEach((slider, index) => {
    slider.classList.add(`cta-slider-${index}`);

    const progressBar = slider.closest("section").querySelector(".cta-slider-progress");

    const swiper = new Swiper(`.cta-slider-${index}`, {
      slidesPerView: 1.1,
      slidesPerGroup: 1,
      spaceBetween: 12,
      centeredSlides: false,
      loop: false,
      breakpoints: {
        640: {
          slidesPerView: 1.5,
          spaceBetween: 12
        },
        768: {
          slidesPerView: 2,
          spaceBetween: 16
        },
        1024: {
          slidesPerView: 3,
          spaceBetween: 16
        },
      },
      on: {
        init: function () {
          updateProgress(this, progressBar);
        },
        slideChange: function () {
          updateProgress(this, progressBar);
        },
        resize: function () {
          updateProgress(this, progressBar);
        },
      },
    });

    function updateProgress(swiper, progressBar) {
      if (!progressBar) return;

      const totalSlides = swiper.slides.length;
      const slidesPerView = swiper.params.slidesPerView === 'auto'
        ? swiper.slidesPerViewDynamic()
        : swiper.params.slidesPerView;

      // Hide progress bar if all slides are visible
      const progressContainer = progressBar.parentElement;
      if (slidesPerView >= totalSlides) {
        progressContainer.style.display = 'none';
        return;
      }
      progressContainer.style.display = '';

      // Calculate progress based on visible portion
      const visibleRatio = Math.min(slidesPerView / totalSlides, 1);
      const scrollableRatio = 1 - visibleRatio;
      const progress = visibleRatio + (scrollableRatio * swiper.progress);

      progressBar.style.width = `${progress * 100}%`;
    }
  });
}
