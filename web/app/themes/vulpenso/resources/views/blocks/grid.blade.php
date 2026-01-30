<x-section
  :id="$id"
  :pt="$pt"
  :pb="$pb"
  :background_color="$background_color"
>
  <div class="container relative z-10">
    {{-- Header section: centered title, text and buttons --}}
    @if(($title && $content_items && in_array('title', $content_items)) || ($subtitle && $content_items && in_array('subtitle', $content_items)) || ($content && $content_items && in_array('content', $content_items)) || ($buttons && $content_items && in_array('buttons', $content_items)))
      <div class="max-w-3xl mx-auto flex flex-col gap-4 mb-12 md:mb-16">
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

    {{-- Grid section --}}
    @if($grid_items && count($grid_items) > 0)
      <div class="grid grid-cols-6 gap-4 md:gap-6" data-reveal-group>
        @foreach ($grid_items as $item)
          <x-cards.grid-item :item="$item" :background_color="$background_color" />
        @endforeach
      </div>
    @endif
  </div>
</x-section>
