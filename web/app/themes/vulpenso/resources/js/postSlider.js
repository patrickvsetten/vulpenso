import Swiper from 'swiper/bundle';

export function initPostSlider() {
  const postSliders = document.querySelectorAll(".post-slider");

  postSliders.forEach((slider, index) => {
    slider.classList.add(`post-slider-${index}`);

    const prevButton = slider.closest("section").querySelector(".swiper-prev");
    const nextButton = slider.closest("section").querySelector(".swiper-next");

    const slideCurrent = slider.closest("section").querySelector(".slide-current");
    const slideTotal = slider.closest("section").querySelector(".slide-total");
    const progressBarFill = slider.closest("section").querySelector(".progressbar-fill");

    const breakpointsConfig = {
      projects: {
        640: { slidesPerView: 1.25, spaceBetween: 24 },
        1024: { slidesPerView: 2, spaceBetween: 32 },
        1440: { slidesPerView: 2.5, spaceBetween: 40 },
      },
      news: {
        640: { slidesPerView: 2, spaceBetween: 24 },
        1024: { slidesPerView: 3, spaceBetween: 32 },
        1440: { slidesPerView: 3, spaceBetween: 40 },
      },
    };

    const sliderType = Object.keys(breakpointsConfig).find((type) =>
      slider.classList.contains(type)
    );

    const breakpoints = breakpointsConfig[sliderType] || {
      640: { slidesPerView: 1, spaceBetween: 16 },
    };

    const swiper = new Swiper(slider, {
      slidesPerView: 1,
      slidesPerGroup: 1,
      spaceBetween: 16,
      breakpoints,
      centeredSlides: false,
      loop: false,
      navigation: {
        prevEl: prevButton,
        nextEl: nextButton,
      },
      on: {
        init: function () {
          updatePagination(this);
        },
        slideChange: function () {
          updatePagination(this);
        },
      },
    });

    function updatePagination(swiper) {
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
  });
}
