<footer class="pb-8 relative">
  {{-- Background shape from bottom up to middle of CTA block --}}
  <div class="hidden lg:block absolute left-4 right-4 bottom-4 top-[12rem] md:top-[16rem] lg:top-[20rem] bg-white/4 -z-1 overflow-clip rounded-2xl lg:rounded-3xl">
    <img src="{{ get_theme_file_uri('resources/images/footer-decoration.svg') }}" alt="Footer shape" class="absolute top-0 bottom-0 right-0 h-full w-auto -z-1 opacity-3">
  </div>

  {{-- Dark background for top portion --}}
  <div class="absolute inset-x-0 top-0 h-[12rem] md:h-[16rem] lg:h-[20rem] bg-dark"></div>

  <div class="relative z-10">
    <div class="container pt-16 md:pt-24 lg:pt-32">
      {{-- CTA Block --}}
      <div class="bg-white rounded-2xl lg:rounded-3xl p-8 md:p-12 lg:p-16 lg:w-10/12 lg:mx-auto">
      <div class="grid md:grid-cols-2">
        {{-- Left Column --}}
        <div class="flex flex-col gap-4">
          @if($cta_left_title)
            <x-content.title
              :title="$cta_left_title"
              heading="h3"
              background="bg-white"
              class="text-xl md:text-2xl lg:text-3xl"
              :split="false"
            />
          @endif
          @if($cta_left_text)
            <x-content.text
              :content="$cta_left_text"
              background="bg-white"
              class="max-w-xs"
            />
          @endif
          @if($cta_left_button)
            <div class="mt-2 flex justify-start">
              <x-content.button
                type="primary"
                :href="$cta_left_button['url']"
                :target="$cta_left_button['target'] ?? '_self'"
                :title="$cta_left_button['title']"
              />
            </div>
          @endif
        </div>

        {{-- Right Column --}}
        <div class="flex flex-col gap-4 border-t border-dark/10 mt-6 pt-6 md:mt-0 md:pt-0 md:border-l md:border-t-0 md:pl-16 lg:pl-24">
          @if($cta_right_title)
            <x-content.title
              :title="$cta_right_title"
              heading="h3"
              background="bg-white"
              class="text-xl md:text-2xl lg:text-3xl"
              :split="false"
            />
          @endif
          @if($cta_right_text)
            <x-content.text
              :content="$cta_right_text"
              background="bg-white"
              class="max-w-xs prose-a:text-primary prose-a:no-underline hover:prose-a:underline"
            />
          @endif
          @if($cta_right_note)
            <p class="text-sm text-dark/50 mt-2">{{ $cta_right_note }}</p>
          @endif
        </div>
      </div>
    </div>

    {{-- Action Buttons --}}
    @if($footer_buttons && count($footer_buttons) > 0)
      <div class="grid md:grid-cols-2 gap-4 mt-4 lg:w-10/12 lg:mx-auto">
        @foreach($footer_buttons as $button)
          @if($button['link'])
            <a
              href="{{ $button['link']['url'] }}"
              target="{{ $button['link']['target'] ?? '_self' }}"
              class="group flex items-center gap-4 bg-white rounded-2xl p-4 md:p-6 transition-all duration-300"
            >
              @if($button['icon_url'])
                <div class="size-14 bg-light rounded-xl grid place-items-center flex-shrink-0">
                  <x-lordicon
                    :src="$button['icon_url']"
                    trigger="hover"
                    target=".group"
                    class="size-8"
                    colors="primary:#C38E66,secondary:#C38E66"
                  />
                </div>
              @endif
              <div class="flex-grow">
                @if($button['label'])
                  <p class="text-sm text-dark/50">{{ $button['label'] }}</p>
                @endif
                <p class="text-lg md:text-xl font-semibold text-dark">{{ $button['link']['title'] }}</p>
              </div>
              <x-arrow-button type="text" class="flex-shrink-0" />
            </a>
          @endif
        @endforeach
      </div>
    @endif
    </div>
  </div>

  <div class="container relative z-10">
    {{-- Footer Content --}}
    <div class="grid md:grid-cols-3 gap-8 md:gap-12 mt-16 md:mt-24">
      {{-- Certificates Column --}}
      <div>
        <h4 class="text-primary font-medium mb-6">{{ $certificates_title }}</h4>
        @if($footer_certificates && count($footer_certificates) > 0)
          <div class="grid grid-cols-2 gap-3 max-w-xs">
            @foreach($footer_certificates as $certificate)
              @if($certificate['link'])
                <a href="{{ $certificate['link'] }}" target="_blank" rel="noopener" class="bg-white rounded-xl p-4 grid place-items-center aspect-[3/2] transition-opacity hover:opacity-80">
                  {!! wp_get_attachment_image($certificate['image'], 'medium', false, ['class' => 'max-h-12 w-auto object-contain']) !!}
                </a>
              @else
                <div class="bg-white/3 border border-white/7 rounded-xl p-4 grid place-items-center aspect-[16/9]">
                  {!! wp_get_attachment_image($certificate['image'], 'medium', false, ['class' => 'max-h-12 max-w-22 w-auto object-contain']) !!}
                </div>
              @endif
            @endforeach
          </div>
        @endif
      </div>

      {{-- Services Column --}}
      <div>
        <h4 class="text-primary font-medium mb-6">{{ $services_title }}</h4>
        @if($services && count($services) > 0)
          @php
            $half = ceil(count($services) / 2);
            $leftServices = array_slice($services, 0, $half);
            $rightServices = array_slice($services, $half);
          @endphp
          <div class="grid grid-cols-2 gap-x-8 gap-y-2">
            <div class="flex flex-col gap-2">
              @foreach($leftServices as $service)
                <a href="{{ $service['url'] }}" class="text-white hover:text-primary transition-colors">
                  {{ $service['title'] }}
                </a>
              @endforeach
            </div>
            <div class="flex flex-col gap-2">
              @foreach($rightServices as $service)
                <a href="{{ $service['url'] }}" class="text-white hover:text-primary transition-colors">
                  {{ $service['title'] }}
                </a>
              @endforeach
            </div>
          </div>
        @endif
      </div>

      {{-- Contact Column --}}
      <div>
        <h4 class="text-primary font-medium mb-6">{{ $contact_title }}</h4>
        @if($contact_address)
          <x-content.text
            :content="$contact_address"
            background="bg-dark"
          />
        @endif
      </div>
    </div>

    {{-- Bottom Bar --}}
    <div class="mt-16 md:mt-24">
      <div class="bg-white/3 border border-white/7 backdrop-blur-lg rounded-xl p-4 md:p-6 lg:p-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <p class="text-white text-sm">{{ $copyright }}</p>
        @if($footer_links && count($footer_links) > 0)
          <div class="flex items-center gap-4">
            @foreach($footer_links as $index => $footerLink)
              @if($footerLink['link'])
                @if($index > 0)
                  <span class="text-white/30">|</span>
                @endif
                <a href="{{ $footerLink['link']['url'] }}" target="{{ $footerLink['link']['target'] ?? '_self' }}" class="text-white/60 text-sm hover:text-white transition-colors underline">
                  {{ $footerLink['link']['title'] }}
                </a>
              @endif
            @endforeach
          </div>
        @endif
      </div>
    </div>
  </div>
</footer>
