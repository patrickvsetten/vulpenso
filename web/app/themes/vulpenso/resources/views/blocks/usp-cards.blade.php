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
    <div class="container relative z-10">
      {{-- Header section: centered title, text and buttons --}}
      @if(($title && $content_items && in_array('title', $content_items)) || ($subtitle && $content_items && in_array('subtitle', $content_items)) || ($content && $content_items && in_array('content', $content_items)) || ($buttons && $content_items && in_array('buttons', $content_items)))
        <div class="max-w-2xl flex flex-col gap-4 mb-12 md:mb-16">
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

      {{-- USP Cards section --}}
      @if($usps && count($usps) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6" data-reveal-group>
          @foreach ($usps as $usp)
            <x-cards.usp-card :usp="$usp" :background_color="$background_color" />
          @endforeach
        </div>
      @endif
    </div>
  </div>
</x-section>
