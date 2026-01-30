<div class="service-menu__overlay fixed inset-0 bg-black/50 opacity-0 pointer-events-none z-40" data-service-menu-overlay></div>

<nav
  x-data="{ sticky: window.scrollY > 24 }"
  x-init="window.addEventListener('scroll', () => sticky = window.scrollY > 24, { passive: true })"
  :class="sticky ? 'is-sticky' : ''"
  class="nav z-50 absolute top-6 inset-x-0 md:absolute md:top-8"
  x-cloak
>
  <div class="container">
    <div class="flex items-center w-full justify-between gap-2">
      <div class="flex items-center justify-between md:justify-start px-4 py-2 md:px-6 md:py-2 gap-2 md:gap-4 lg:gap-8 bg-black/70 w-full md:w-auto border border-white/5 backdrop-blur-md rounded-xl isolate will-change-transform [transform:translate3d(0,0,0)]">
        <a href="{{ home_url('/') }}" class="relative py-2">
          <div>
            <img src="{{ get_theme_file_uri('resources/images/logo.svg') }}" alt="Logo" class="h-8 object-center">
          </div>
        </a>
        @if (has_nav_menu('primary_navigation'))
          <div class="hidden md:block bg-white/10 h-4 w-px"></div>
          <div class="hidden lg:block" aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}">
            {!! wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav-primary text-white text-sm uppercase font-semibold flex items-center gap-4 lg:gap-8 text-md', 'echo' => false, 'walker' => new \App\Walkers\Primary_Nav_Walker()]) !!}
          </div>
        @endif
        @if ($phone_general)
          <a href="tel:{!! $phone_general !!}" class="md:hidden grid place-items-center size-12 rounded-xl bg-primary/10">
            <x-lordicon
              src="wired-outline-2806-smartphone-3-hover-phone-ring-alt"
              trigger="hover"
              target=".group"
              stroke="bold"
              class="icon-lottie size-6"
              primary="#C38E66"
              secondary="#C38E66"
            />
          </a>
        @endif
      </div>
      <div class="hidden md:flex items-center justify-end gap-2 p-2 bg-black/70 border border-white/5 backdrop-blur-lg rounded-xl isolate will-change-transform [transform:translate3d(0,0,0)]">
        <div data-service-menu data-service-menu-status="not-active" class="service-menu relative hidden lg:block">
          <div class="service-menu__bg absolute top-0 right-0 rounded-lg bg-white pointer-events-none"></div>
          <x-content.button
            type="outline"
            icon="plus"
            title="Wat we doen"
            class="service-menu__trigger cursor-pointer"
            data-service-menu-toggle
          />
          <div class="service-menu__content absolute top-full right-0 pointer-events-none opacity-0 invisible origin-top-right">
            <div class="service-menu__content-inner">
              <ul class="flex flex-col gap-1 p-3 pt-4 pb-8">
                @foreach ($services as $service)
                  <li class="service-menu__item list-none">
                    <a href="{{ $service['link'] }}" class="service-menu__link group flex items-center gap-4 py-2 px-8 rounded-xl text-dark font-semibold text-base transition-all duration-300 hover:bg-primary/10 hover:text-primary">
                      <div class="size-10 lg:size-12 bg-primary/10 rounded-lg grid place-items-center flex-shrink-0">
                        <x-lordicon
                          :src="$service['icon_url']"
                          trigger="hover"
                          target=".group"
                          stroke="bold"
                          class="icon-lottie size-6 lg:size-8"
                          primary="#C38E66"
                          secondary="#C38E66"
                        />
                      </div>
                      <span class="leading-tight whitespace-nowrap lg:text-lg">{!! $service['title'] !!}</span>
                      <div class="service-menu__dot ml-auto size-2 rounded-full bg-current opacity-0 scale-0 transition-all duration-500"></div>
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
        <x-content.button
          :href="get_permalink(282)"
          type="primary"
          title="Plan onderhoud"
          class="hidden md:inline-flex"
        >
        </x-button>
        <div class="lg:hidden relative">
          <div class="hamburger">
            <div class="hamburger__icon rounded-lg size-14 bg-dark flex flex-col space-y-1 items-center justify-center cursor-pointer">
              <span class="hamburger--line bg-white h-[1.5px] w-5 block ease-in-out duration-300"></span>
              <span class="hamburger--line bg-white h-[1.5px] w-5 block ease-in-out duration-300"></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</nav>

<div class="mobile-nav fixed pointer-events-none flex items-center z-[49] inset-0 transform-gpu opacity-0 ease-in-out duration-500 bg-white">
  <div class="max-h-dvh h-full overflow-y-auto pt-24 md:pt-40 w-full">
    <div class="container">
      <div class="grid items-start">
        @if (has_nav_menu('mobile_navigation'))
          <div aria-label="{{ wp_get_nav_menu_name('mobile_navigation') }}" class="w-full mb-8">
            {!! wp_nav_menu(['theme_location' => 'mobile_navigation', 'menu_class' => 'nav-mobile flex flex-col text-lg text-black', 'echo' => false]) !!}
          </div>
        @endif
        <x-content.button
          href="/contact"
          type="yellow"
          title="Contact"
          class="sticky bottom-4"
        />
      </div>
    </div>
  </div>
</div>

{{-- Desktop Bottom Bar --}}
<x-navigation.desktop-bottom-bar />

{{-- WhatsApp Modal --}}
<x-navigation.whatsapp-modal :url="$whatsapp_url" />

{{-- Mobile Bottom Navigation --}}
<x-navigation.mobile-bottom-nav
  :items="$mobile_menu_items"
  :secondaryMenu="$secondary_mobile_menu"
  :whatsappUrl="$whatsapp_url"
/>

@if ($menu_spacer == true)
  <div class="w-full h-12 md:h-32 lg:h-40"></div>
@endif