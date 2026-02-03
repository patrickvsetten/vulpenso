<x-section
  :id="$id"
  :pt="$pt"
  :pb="$pb"
  background_color="bg-dark"
>
  <div @class([
    $background_color => $background_color && $background_color !== 'bg-dark',
    'py-12 md:py-16 lg:py-20 xl:py-24 relative overflow-clip lg:mx-4 rounded-xl md:rounded-3xl' => $background_color !== 'bg-dark',
  ])> 
    <div class="container">
      {{-- Header --}}
      @if(($title && $content_items && in_array('title', $content_items)) || ($subtitle && $content_items && in_array('subtitle', $content_items)))
        <div class="flex flex-col gap-2 mb-8 md:mb-12" data-reveal-group>
          <x-content.subtitle
            :subtitle="$subtitle"
            :background="$background_color"
            :contentItems="$content_items"
          />
          <x-content.title
            :title="$title"
            :heading="$heading"
            split="false"
            :background="$background_color"
            :contentItems="$content_items"
          />
        </div>
      @endif

      {{-- Employees Grid --}}
      @if ($employees && count($employees) > 0)
        <div
          data-momentum-hover-init
          class="flex flex-wrap justify-center gap-8"
        >
          @foreach ($employees as $index => $employee)
            <div class="w-full sm:w-[calc(50%-1rem)] lg:w-[calc(25%-1.5rem)] relative">
              {{-- Employee Card --}}
              <div data-momentum-hover-element class="w-full relative">
                <div
                  data-momentum-hover-target
                  @class([
                    'employee-card relative w-full rounded-3xl overflow-hidden',
                    'text-white' => $background_color === 'bg-dark',
                    'text-dark' => $background_color !== 'bg-dark',
                  ])
                >
                  {{-- Aspect ratio placeholder --}}
                  <div class="pt-[100%] md:pt-[150%]"></div>

                  {{-- Image --}}
                  @if($employee['thumbnail'])
                    {!! wp_get_attachment_image($employee['thumbnail'], 'large', false, [
                      'class' => 'absolute inset-0 w-full h-full object-cover'
                    ]) !!}
                  @endif

                  {{-- Content overlay --}}
                  <div class="absolute inset-0 flex flex-col justify-end p-6 md:p-8 bg-gradient-to-t from-black/60 via-transparent to-transparent">
                    <div class="flex items-center gap-1">
                      <h3 class="text-xl md:text-2xl font-medium leading-none text-white">
                        {{ $employee['name'] }}
                      </h3>
                    </div>
                    @if($employee['function'])
                      <p class="text-white/70 text-base mt-2">
                        {{ $employee['function'] }}
                      </p>
                    @endif
                  </div>
                </div>
              </div>

              {{-- Duurzaam Badge - show on 3rd card (index 2) --}}
              @if($index === 2)
                <div data-momentum-hover-element class="hidden md:block absolute z-10 size-24 lg:size-32 top-0 rotate-10 right-0 translate-x-1/2 -translate-y-1/2">
                  <div data-momentum-hover-target class="w-full h-full">
                    <img
                      src="{{ get_theme_file_uri('resources/images/duurzaam.svg') }}"
                      alt="Duurzaam ondernemen"
                      class="w-full h-auto rounded-full"
                    >
                  </div>
                </div>
              @endif
            </div>
          @endforeach
        </div>
      @else
        <p @class([
          'text-white/50' => $background_color === 'bg-dark',
          'text-dark/50' => $background_color !== 'bg-dark',
        ])>
          Nog geen medewerkers toegevoegd.
        </p>
      @endif
    </div>
  </div>
</x-section>
