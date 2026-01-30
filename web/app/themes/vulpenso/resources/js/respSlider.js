import Swiper from 'swiper/bundle';

export function initRespSlider($) {
  let isInitialized = false;
  const swiperInstances = [];

  function initializeResponsiveSwiper() {
    const isMobile = window.innerWidth < 1024;

    if (isMobile && !isInitialized) {
      isInitialized = true;

      const sliders = document.querySelectorAll(".resp-slider");
      const nextButtons = document.querySelectorAll(".resp-next");
      const prevButtons = document.querySelectorAll(".resp-prev");

      sliders.forEach((slider, index) => {
        slider.classList.add(`resp-slider-${index}`);
        if (nextButtons[index]) nextButtons[index].classList.add(`resp-next-${index}`);
        if (prevButtons[index]) prevButtons[index].classList.add(`resp-prev-${index}`);

        const slideCurrent = slider.closest("section").querySelector(".slide-current");
        const slideTotal = slider.closest("section").querySelector(".slide-total");
        const progressBarFill = slider.closest("section").querySelector(".progressbar-fill");

        swiperInstances[index] = new Swiper(`.resp-slider-${index}`, {
          slidesPerView: 1,
          spaceBetween: 16,
          breakpoints: {
            640: {
              slidesPerView: 1.75,
              spaceBetween: 16,
            },
            768: {
              slidesPerView: 2.25,
              spaceBetween: 24,
            },
          },
          loop: true,
          loopAdditionalSlides: 1,
          navigation: {
            prevEl: `.resp-prev-${index}`,
            nextEl: `.resp-next-${index}`,
          },
          on: {
            init: function () {
              updatePagination(this, slideCurrent, slideTotal, progressBarFill);
            },
            slideChange: function () {
              updatePagination(this, slideCurrent, slideTotal, progressBarFill);
            },
          },
        });
      });
    } else if (!isMobile && isInitialized) {
      swiperInstances.forEach((instance, index) => {
        if (instance) {
          instance.destroy(true, true);
          swiperInstances[index] = null;
        }
      });
      isInitialized = false;
    }
  }

  function updatePagination(swiper, slideCurrent, slideTotal, progressBarFill) {
    if (!slideCurrent || !slideTotal || !progressBarFill) return;

    const slidesPerView = getSlidesPerViewForCurrentBreakpoint(swiper);
    const totalSlides = swiper.slides.length;

    if (!totalSlides || !slidesPerView) return;

    const totalPages = Math.ceil(totalSlides - slidesPerView + 1);
    const currentPage = Math.ceil(swiper.activeIndex + 1);

    slideTotal.textContent = String(totalPages).padStart(2, '0');
    slideCurrent.textContent = String(currentPage).padStart(2, '0');

    const progress = ((swiper.activeIndex + 1) / totalPages) * 100;
    progressBarFill.style.width = `${progress}%`;
  }

  function getSlidesPerViewForCurrentBreakpoint(swiper) {
    const windowWidth = window.innerWidth;
    const breakpoints = swiper.params.breakpoints;
    let slidesPerView = 1;

    Object.keys(breakpoints).forEach((breakpoint) => {
      if (windowWidth >= breakpoint) {
        slidesPerView = breakpoints[breakpoint].slidesPerView;
      }
    });

    return slidesPerView;
  }

  initializeResponsiveSwiper();
  window.addEventListener("resize", initializeResponsiveSwiper);
}
