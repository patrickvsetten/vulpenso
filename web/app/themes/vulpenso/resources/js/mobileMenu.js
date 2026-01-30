export function initMobileMenu($) {
  
  // HAMBURGER
  $('.hamburger').click(function () {
    $('body').toggleClass('hamburger-active');
    $('li.menu-item-has-children').removeClass('active');
  });

  // HAMBURGER SUBMENU
  $('.mobile-nav .sub-menu').wrapInner('<div class="submenu-wrapper w-full mx-auto flex justify-between flex-wrap items-center"></div>');
  $('.mobile-nav .nav-mobile li.menu-item-has-children').click(function () {
    if ($(this).hasClass('active')) {
      $(this).removeClass('active');
    } else {
      $('.mobile-nav .menu-mobile-container ul li.menu-item-has-children').removeClass('active');
      $(this).addClass('active');
    }
  });

}
