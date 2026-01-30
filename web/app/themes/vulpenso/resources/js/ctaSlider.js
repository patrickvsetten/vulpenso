import Swiper from 'swiper/bundle';

export function initCTASlider() {
  const ctaSliders = document.querySelectorAll(".cta-slider");

  ctaSliders.forEach((slider, index) => {
    slider.classList.add(`cta-slider-${index}`);

    const prevButton = slider.closest("section").querySelector(".cta-slider-prev");
    const nextButton = slider.closest("section").querySelector(".cta-slider-next");


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
        // 1280: {
        //   slidesPerView: 3,
        //   spaceBetween: 24
        // },
      },
      navigation: {
        prevEl: prevButton,
        nextEl: nextButton,
      },
      on: {
        init: function () {
          updateNavigationState(this, prevButton, nextButton);
        },
        slideChange: function () {
          updateNavigationState(this, prevButton, nextButton);
        },
      },
    });

    function updateNavigationState(swiper, prevButton, nextButton) {
      if (prevButton) {
        if (swiper.isBeginning) {
          prevButton.classList.add('swiper-button-disabled');
          prevButton.setAttribute('disabled', 'disabled');
        } else {
          prevButton.classList.remove('swiper-button-disabled');
          prevButton.removeAttribute('disabled');
        }
      }

      if (nextButton) {
        if (swiper.isEnd) {
          nextButton.classList.add('swiper-button-disabled');
          nextButton.setAttribute('disabled', 'disabled');
        } else {
          nextButton.classList.remove('swiper-button-disabled');
          nextButton.removeAttribute('disabled');
        }
      }
    }
  });
}
