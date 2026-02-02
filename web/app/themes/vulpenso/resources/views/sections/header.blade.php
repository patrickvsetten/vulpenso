{{-- Service Menu Overlay --}}
<div class="service-menu__overlay fixed inset-0 bg-black/80 opacity-0 pointer-events-none z-40 transition-opacity duration-300" data-service-menu-overlay></div>

<nav
  x-data="{ sticky: window.scrollY > 24 }"
  x-init="window.addEventListener('scroll', () => sticky = window.scrollY > 24, { passive: true })"
  :class="sticky ? 'is-sticky' : ''"
  class="nav z-50 absolute top-6 inset-x-0 md:absolute md:top-8"
  data-service-menu
  data-service-menu-status="not-active"
  x-cloak
>
  <div class="container relative z-10">
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
        {{-- Service Menu Trigger Button --}}
        <x-content.button
          type="white"
          icon="plus"
          title="Wat we doen"
          class="service-menu__trigger hidden lg:inline-flex cursor-pointer"
          data-service-menu-toggle
        />

        <x-content.button
          :href="get_permalink(282)"
          type="primary"
          title="Plan onderhoud"
          class="hidden lg:inline-flex"
        />
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

{{-- Service Menu Full-Width Dropdown --}}
<div
  class="service-menu__dropdown fixed left-0 right-0 z-45 bg-dark rounded-b-2xl opacity-0 invisible pointer-events-none transition-all duration-500 ease-[cubic-bezier(0.525,0,0,1)]"
  data-service-dropdown
  x-data="{ activeIndex: null }"
>
  <img src="{{ get_theme_file_uri('resources/images/footer-decoration.svg') }}" alt="Footer shape" class="absolute top-0 bottom-0 right-0 h-full w-auto -z-1 opacity-3">
  <div class="relative z-10 container py-8 lg:py-12">
    <div class="mx-auto lg:max-w-4xl grid grid-cols-2 gap-12 lg:gap-24">
      {{-- Links: Menu items --}}
      <div class="flex flex-col" @mouseleave="activeIndex = null">
        <span class="text-white/40 text-sm mb-4 flex items-center gap-2">
          Onze diensten
        </span>
        <ul class="flex flex-col gap-2">
          @foreach ($services as $index => $service)
            <li>
              <a
                href="{{ $service['link'] }}"
                class="service-menu__link group flex items-center gap-4 px-4 py-3 rounded-lg text-lg font-medium transition-all duration-300"
                :class="activeIndex === {{ $index }} ? 'bg-primary/10 text-primary' : 'text-white hover:text-white/80'"
                @mouseenter="activeIndex = {{ $index }}"
                data-index="{{ $index }}"
              >
                @if(!empty($service['icon_url']))
                  <div class="size-10 lg:size-12 rounded-lg grid place-items-center transition-all duration-300" :class="activeIndex === {{ $index }} ? 'bg-primary/20' : 'bg-white/5'">
                    <x-lordicon
                      :src="$service['icon_url']"
                      trigger="hover"
                      target="a"
                      class="icon-lottie size-5 lg:size-6"
                      :colors="'primary:' . ($index === 0 ? '#C38E66' : '#FFFFFF') . ',secondary:' . ($index === 0 ? '#C38E66' : '#FFFFFF')"
                      x-bind:colors="activeIndex === {{ $index }} ? 'primary:#C38E66,secondary:#C38E66' : 'primary:#FFFFFF,secondary:#FFFFFF'"
                    />
                  </div>
                @endif
                <span :class="activeIndex === {{ $index }} ? 'font-semibold' : ''">{{ $service['title'] }}</span>
                <x-arrow-button size="xs" type="outline-to-primary" class="ml-auto" />
              </a>
            </li>
          @endforeach
        </ul>
      </div>

      {{-- Rechts: Preview card --}}
      <div class="relative min-h-[20rem]">
        {{-- Default afbeelding wanneer niets geselecteerd --}}
        @if($service_menu_image)
          <div
            class="service-menu__preview absolute inset-0 rounded-2xl overflow-hidden"
            :class="activeIndex === null ? 'opacity-100' : 'opacity-0 pointer-events-none'"
          >
            @if(is_array($service_menu_image))
              {!! wp_get_attachment_image($service_menu_image['ID'], 'large', false, ['class' => 'absolute inset-0 w-full h-full object-cover']) !!}
            @else
              {!! wp_get_attachment_image($service_menu_image, 'large', false, ['class' => 'absolute inset-0 w-full h-full object-cover']) !!}
            @endif
            <div class="absolute inset-0 bg-black/20"></div>
          </div>
        @endif

        {{-- Service afbeeldingen --}}
        @foreach ($services as $index => $service)
          <div
            class="service-menu__preview absolute inset-0 rounded-2xl overflow-hidden"
            :class="activeIndex === {{ $index }} ? 'opacity-100' : 'opacity-0 pointer-events-none'"
          >
            @if(!empty($service['image']))
              @if(is_array($service['image']))
                {!! wp_get_attachment_image($service['image']['ID'], 'large', false, ['class' => 'absolute inset-0 w-full h-full object-cover']) !!}
              @else
                {!! wp_get_attachment_image($service['image'], 'large', false, ['class' => 'absolute inset-0 w-full h-full object-cover']) !!}
              @endif
            @endif
            <div class="absolute inset-0 bg-black/20"></div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</div>

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