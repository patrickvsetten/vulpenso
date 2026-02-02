<x-section
  :id="$id"
  :pt="$pt"
  :pb="$pb"
  :background_color="$background_color"
>
  <div class="container relative z-10">
    {{-- Header section: centered title, text and buttons --}}
    @if(($title && $content_items && in_array('title', $content_items)) || ($subtitle && $content_items && in_array('subtitle', $content_items)) || ($content && $content_items && in_array('content', $content_items)) || ($buttons && $content_items && in_array('buttons', $content_items)))
      <div class="flex flex-col gap-4 mb-12 md:mb-16">
        <x-content.subtitle
          :subtitle="$subtitle"
          :contentItems="$content_items"
          :background="$background_color"
          class="text-orange"
        />
        <x-content.title
          :title="$title"
          :heading="$heading"
          :contentItems="$content_items"
          :background="$background_color"
          class="text-xl md:text-2xl lg:text-3xl leading-normal"
        />
        <x-content.text
          :content="$content"
          :contentItems="$content_items"
          :background="$background_color"
          class="max-w-full"
        />
        @if ($buttons && $content_items && in_array('buttons', $content_items))
          <div class="flex flex-wrap items-center justify-center gap-4 mt-2">
            <x-content.buttons :buttons="$buttons" />
          </div>
        @endif
      </div>
    @endif

    {{-- Prices section with sticky nav --}}
    @if($price_tables && count($price_tables) > 0)
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 md:gap-16 lg:gap-24">
        {{-- Sticky navigation (1/3) --}}
        <div class="lg:col-span-1">
          <nav @class([
            'lg:sticky lg:top-32 rounded-xl border overflow-hidden relative p-4 md:p-6 lg:p-8',
            'bg-dark/3 border-dark/7 text-dark' => $background_color === 'bg-white',
            'bg-white/3 border-white/7 text-white' => $background_color !== 'bg-white',
          ])>
            <p class="mb-4 text-white/40 text-sm">Categorieën</p>
            <ul class="flex flex-col gap-2">
              @foreach ($price_tables as $index => $table)
                <li>
                  <a
                    href="#{{ $table['slug'] }}"
                    @class([
                      'prices-nav__link flex items-center gap-4 px-4 py-3 rounded-lg transition-all duration-300 font-bold',
                      'hover:bg-primary/5 hover:text-primary text-dark' => $background_color === 'bg-white',
                      'hover:bg-primary/5 hover:text-primary text-white' => $background_color !== 'bg-white',
                    ])
                    data-prices-nav-link
                  >
                    <span>{{ $table['title'] }}</span>
                    <div class="prices-nav__dot ml-auto size-2 rounded-full bg-current opacity-0 scale-0 transition-all duration-500"></div>
                  </a>
                </li>
              @endforeach
            </ul>
          </nav>
        </div>

        {{-- Price tables (2/3) --}}
        <div class="lg:col-span-2 flex flex-col gap-12 lg:gap-16">
          @foreach ($price_tables as $index => $table)
            <div id="{{ $table['slug'] }}" class="scroll-mt-20" data-prices-table>
              {{-- Table title --}}
              @if($table['title'])
                <h3 @class([
                  'font-semibold text-xl md:text-2xl mb-2',
                  'text-dark' => $background_color === 'bg-white',
                  'text-white' => $background_color !== 'bg-white',
                ])>
                  {{ $table['title'] }}
                </h3>
              @endif

              {{-- Table description --}}
              @if($table['description'])
                <div @class([
                  'mb-6 prose lg:prose-lg',
                  'text-dark' => $background_color === 'bg-white',
                  'text-white' => $background_color !== 'bg-white',
                ])>
                  {!! $table['description'] !!}
                </div>
              @endif

              {{-- Price list with directional hover --}}
              @if($table['items'] && count($table['items']) > 0)
                <div data-directional-hover data-type="y" @class([
                  'directional-list flex flex-col w-full relative',
                  'text-dark' => $background_color === 'bg-white',
                  'text-white' => $background_color !== 'bg-white',
                ])>
                  {{-- Header row --}}
                  <div class="directional-list__info flex justify-between items-center w-full gap-4 pb-4 px-4 md:px-6 relative">
                    <div class="flex-1">
                      <p class="text-xs uppercase tracking-widest text-primary leading-none mb-0">Werkzaamheden</p>
                    </div>
                    <div class="flex-none min-w-[6rem] text-right">
                      <p class="text-xs uppercase tracking-widest text-primary leading-none mb-0">Prijs incl. BTW</p>
                    </div>
                  </div>

                  {{-- Items --}}
                  <div class="directional-list__collection">
                    <div class="directional-list__list">
                      @foreach ($table['items'] as $item)
                        <div data-directional-hover-item @class([
                          'directional-list__item flex justify-between items-center gap-4 py-6 md:py-8 px-4 md:px-6 relative overflow-hidden -mt-px',
                          'text-dark' => $background_color === 'bg-white',
                          'text-white' => $background_color !== 'bg-white',
                        ])>
                          <div data-directional-hover-tile class="directional-list__hover-tile absolute inset-0 bg-white/5"></div>
                          <div @class([
                            'directional-list__border absolute top-0 left-0 w-full h-px z-[2]',
                            'bg-dark/20' => $background_color === 'bg-white',
                            'bg-white/20' => $background_color !== 'bg-white',
                          ])></div>
                          <div class="flex-1 relative z-[1]">
                            <p class="text-base leading-none mb-0 directional-list__text">{{ $item['name'] }}</p>
                          </div>
                          <div class="flex-none min-w-[6rem] text-right relative z-[1]">
                            <p class="text-base leading-none mb-0 whitespace-nowrap directional-list__text">{{ $item['price'] }}</p>
                          </div>
                        </div>
                      @endforeach
                    </div>
                  </div>
                  {{-- Bottom border --}}
                  <div @class([
                    'absolute bottom-0 left-0 w-full h-px z-[2]',
                    'bg-dark/20' => $background_color === 'bg-white',
                    'bg-white/20' => $background_color !== 'bg-white',
                  ])></div>
                </div>
              @endif

              {{-- Additional info --}}
              @if($table['additional_info'])
                <div @class([
                  'mt-4 text-sm prose',
                  'text-dark/60' => $background_color === 'bg-white',
                  'text-white/60 prose-invert' => $background_color !== 'bg-white',
                ])>
                  {!! $table['additional_info'] !!}
              </div>
              @endif
            </div>
          @endforeach
        </div>
      </div>
    @endif
  </div>
</x-section>
