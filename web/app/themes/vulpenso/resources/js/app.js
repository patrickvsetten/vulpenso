import $ from 'jquery';

// Initialize lordicon with lottie-light (SVG only, ~70KB vs ~250KB full version)
// This is loaded immediately for faster icon rendering
import lottie from 'lottie-web/build/player/lottie_light';
import { defineElement } from "@lordicon/element";
defineElement(lottie.loadAnimation);

import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import collapse from '@alpinejs/collapse';

import { initWhatsAppModal } from './whatsappModal';
import { initPostSlider } from './postSlider';
import { initRespSlider } from './respSlider';
import { initMobileMenu } from './mobileMenu';
import { initScrollAnimations } from './scrollAnimations';
import { initMarquee } from './marquee';
import { initCTASlider } from './ctaSlider';
import { initMomentumHover } from './momentumHover';
import { initPricesNav } from './pricesNav';
import { initScrollableText } from './scrollableText';
import { initRotatingText } from './rotatingText';
import { initServiceMenu } from './serviceMenu';
import { initDirectionalHover } from './directionalHover';

// Initialize Alpine.js plugins before DOM loads
Alpine.plugin(collapse);

// Make Alpine available globally for Livewire
window.Alpine = Alpine;

/**
 * Application entrypoint
 */
document.addEventListener('DOMContentLoaded', async () => {

   // WHATSAPP MODAL
   initWhatsAppModal();

  // RESP SLIDER
  if (document.querySelector('.post-slider')) {
    initPostSlider($);
  }

  // CTA SLIDER
  if (document.querySelector('.cta-slider')) {
    initCTASlider();
  }

  // RESP SLIDER
  if (document.querySelector('.resp-slider')) {
    initRespSlider($);
  }

  // MOBILE MENU
  if (document.querySelector('.hamburger')) {
    initMobileMenu($);
  }

  // SCROLL ANIMATIONS
  initScrollAnimations($);

  // MARQUEE
  if (document.querySelector('[data-marquee]')) {
    initMarquee();
  }

  // MOMENTUM HOVER (Employee cards)
  if (document.querySelector('[data-momentum-hover-init]')) {
    initMomentumHover();
  }

  // PRICES NAV
  if (document.querySelector('[data-prices-nav-link]')) {
    initPricesNav();
  }

  // SCROLLABLE TEXT
  if (document.querySelector('[data-scrollable-text]')) {
    initScrollableText();
  }

  // ROTATING TEXT
  if (document.querySelector('[data-rotating-title]')) {
    initRotatingText();
  }

  // SERVICE MENU
  if (document.querySelector('[data-service-menu]')) {
    initServiceMenu();
  }

  // DIRECTIONAL HOVER
  if (document.querySelector('[data-directional-hover]')) {
    initDirectionalHover();
  }

  // Start Livewire (which also starts Alpine.js)
  Livewire.start();

});

/**
 * @see {@link https://webpack.js.org/api/hot-module-replacement/}
 */
if (import.meta.webpackHot) import.meta.webpackHot.accept(console.error);
