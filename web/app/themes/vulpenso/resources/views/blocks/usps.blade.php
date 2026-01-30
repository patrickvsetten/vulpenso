<x-section
  :id="$id"
  :pt="$pt"
  :pb="$pb"
  :background_color="$background_color"
>
  @if ($usps)
    <div class="container">
      <div class="bg-white/10 h-px w-full"></div>
    </div>
    <div
      data-marquee
      data-marquee-duplicate="2"
      data-marquee-direction="left"
      data-marquee-status="normal"
      data-marquee-speed="25"
      data-marquee-scroll-speed="10"
      class="w-full relative overflow-hidden py-4 md:py-8 lg:py-12"
    >
      <div data-marquee-scroll class="flex will-change-transform">
        <div data-marquee-collection class="flex items-center shrink-0 will-change-transform">
          @foreach ($usps as $usp)
            <div class="flex items-center gap-4 group px-4 md:px-6 lg:px-8">
              @if (!empty($usp['icon_url']))
                <div class="size-12 bg-primary/10 rounded-lg grid place-items-center">
                  <x-lordicon
                    :src="$usp['icon_url']"
                    trigger="hover"
                    target=".group"
                    stroke="bold"
                    class="icon-lottie size-8"
                    primary="#C38E66"
                    secondary="#C38E66"
                  />
                </div>
              @endif
              @if($usp['title'])
                <span class="font-medium text-white text-sm md:text-base whitespace-nowrap">
                  {!! $usp['title'] !!}
                </span>
              @endif
            </div>
          @endforeach
        </div>
      </div>
    </div>
    <div class="container">
      <div class="bg-white/10 h-px w-full"></div>
    </div>
  @endif
</x-section>
