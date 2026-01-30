@props(['items', 'secondaryMenu', 'whatsappUrl' => ''])

@php
  // Build WhatsApp link from number if it's not already a URL
  $whatsappLink = $whatsappUrl;
  if ($whatsappUrl && !str_starts_with($whatsappUrl, 'http')) {
    $cleanNumber = preg_replace('/[^0-9]/', '', $whatsappUrl);
    $whatsappLink = 'https://wa.me/' . $cleanNumber;
  }
@endphp

<div
  x-data="{ moreMenuOpen: false, showItems: false }"
  class="fixed inset-x-0 bottom-0 z-50 md:hidden"
>
  {{-- Secondary Mobile Menu Overlay --}}
  <div
    x-cloak
    x-show="moreMenuOpen"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="translate-y-full"
    x-transition:enter-end="translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="translate-y-0"
    x-transition:leave-end="translate-y-full"
    @click.away="moreMenuOpen = false; showItems = false"
    class="absolute bottom-full left-0 right-0 bg-dark/70 backdrop-blur-lg border-t border-white/10 rounded-t-2xl p-6 pb-8 max-h-[70vh] overflow-y-auto isolate will-change-transform [transform:translate3d(0,0,0)]"
  >
    @if (count($secondaryMenu) > 0)
      <nav aria-label="{{ wp_get_nav_menu_name('secondary_mobile_navigation') }}">
        <ul class="grid divide-y divide-white/10">
          @foreach ($secondaryMenu as $index => $menu_item)
            <li
              class="transition-all duration-300 ease-out"
              :class="showItems ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
              style="transition-delay: {{ $index * 75 }}ms"
              @if(count($menu_item['children']) > 0) x-data="{ expanded: false }" @endif
            >
              @if(count($menu_item['children']) > 0)
                {{-- Parent met subitems --}}
                <button
                  @click="expanded = !expanded"
                  class="w-full flex items-center justify-between text-white font-medium text-lg py-3 rounded-xl hover:bg-white/10 transition-colors duration-200"
                >
                  <span>{{ $menu_item['title'] }}</span>
                  <div class="relative z-10 size-6 ml-4 text-primary bg-primary/10 rounded-lg grid place-items-center">
                    <svg 
                      class="stroke-current size-2 transition-transform duration-300 ease-in-out" 
                      :class="expanded ? 'rotate-45' : ''"
                      viewBox="0 0 14 14" 
                      fill="none" 
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path d="M7 1V13M13 7H1" stroke="" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                  </div>
                  {{-- <svg
                    class="size-4 transition-transform duration-200"
                    :class="expanded ? 'rotate-180' : ''"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg> --}}
                </button>
                {{-- Subitems --}}
                <ul
                  x-show="expanded"
                  x-collapse
                  class="mt-1 grid py-2 border-t border-white/10"
                >
                  @foreach ($menu_item['children'] as $child)
                    <li>
                      <a
                        href="{{ $child['url'] }}"
                        class="flex items-center font-medium py-2 rounded-lg hover:bg-white/10 transition-colors duration-200 {{ $child['is_active'] ?? false ? 'text-primary' : 'text-white/70 hover:text-white' }}"
                        @if($child['target']) target="{{ $child['target'] }}" @endif
                      >
                        {{ $child['title'] }}
                      </a>
                    </li>
                  @endforeach
                </ul>
              @else
                {{-- Normaal menu item --}}
                <a
                  href="{{ $menu_item['url'] }}"
                  class="flex items-center gap-4 font-medium text-lg py-3 rounded-xl hover:bg-white/10 transition-colors duration-200 {{ $menu_item['is_active'] ?? false ? 'text-primary' : 'text-white' }}"
                  @if($menu_item['target']) target="{{ $menu_item['target'] }}" @endif
                >
                  {{ $menu_item['title'] }}
                </a>
              @endif
            </li>
          @endforeach
        </ul>
      </nav>
    @endif
  </div>

  {{-- Bottom Navigation Bar --}}
  <div class="bg-dark/70 backdrop-blur-lg border-t border-white/10 safe-area-pb isolate will-change-transform [transform:translate3d(0,0,0)]">
    <div class="grid grid-cols-4 h-16">
      {{-- Menu Items from WordPress mobile_navigation menu (first 3) --}}
      @foreach ($items as $index => $item)
        @php
          $isWhatsApp = $loop->iteration === 3 && $whatsappUrl;
          $isActive = $item['is_active'] ?? false;
          $iconColor = $isActive ? '#C38E66' : '#FFFFFF';
        @endphp
        <a
          href="{{ $isWhatsApp ? $whatsappLink : $item['url'] }}"
          @if($isWhatsApp) target="_blank" @elseif($item['target']) target="{{ $item['target'] }}" @endif
          class="group flex items-center justify-center transition-colors duration-200 {{ $isActive ? 'text-primary' : 'text-white hover:text-white' }}"
        >
          <div class="flex flex-col items-center justify-center gap-1 px-3 py-1.5 rounded-lg {{ $isActive ? 'bg-primary/10' : '' }}">
            @if($item['icon_url'])
              <x-lordicon
                :src="$item['icon_url']"
                trigger="hover"
                target=".group"
                stroke="bold"
                class="icon-lottie size-6"
                :primary="$iconColor"
                :secondary="$iconColor"
              />
            @else
              <div class="size-6 rounded-full bg-white/20"></div>
            @endif
            <span class="text-xs font-medium">{{ $item['title'] }}</span>
          </div>
        </a>
      @endforeach

      {{-- Hamburger / More Button --}}
      <button
        @click="moreMenuOpen = !moreMenuOpen; if(moreMenuOpen) { setTimeout(() => showItems = true, 100) } else { showItems = false }"
        class="group flex flex-col items-center justify-center gap-1 text-white hover:text-white transition-colors duration-200"
        :class="moreMenuOpen ? 'text-primary' : ''"
      >
        <div class="flex flex-col gap-1 items-center justify-center size-6">
          <span class="bg-current h-[1.5px] w-4 block transition-transform duration-300" :class="moreMenuOpen ? 'rotate-45 translate-y-[3px]' : ''"></span>
          <span class="bg-current h-[1.5px] w-4 block transition-transform duration-300" :class="moreMenuOpen ? '-rotate-45 -translate-y-[3px]' : ''"></span>
        </div>
        <span class="text-xs font-medium">Meer</span>
      </button>
    </div>
  </div>
</div>
