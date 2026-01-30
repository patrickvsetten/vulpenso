<x-section
  :id="$id"
  :pt="$pt"
  :pb="$pb"
  background_color="bg-dark"
  class="overflow-x-clip"
>
  <div @class([
    $background_color => $background_color && $background_color !== 'bg-dark',
    'py-12 md:py-16 lg:py-20 xl:py-24 relative overflow-clip lg:mx-4 rounded-xl md:rounded-3xl' => $background_color && $background_color !== 'bg-dark',
  ])>
    <div class="container">
      <div class="space-y-8 md:space-y-12 lg:space-y-16">
        @if(!empty($subtitle) || !empty($title) || !empty($content) || !empty($buttons))
          <div data-reveal-group class="flex flex-col lg:flex-row items-start justify-between gap-4 md:gap-16 lg:gap-32 xl:gap-40">
            <div class="">
              <x-content.subtitle
                :background="$background_color"
                :subtitle="$subtitle"
                :contentItems="$content_items"
              />
            </div>
            <div data-reveal-group class="space-y-4 md:space-y-8 flex-1">
              <x-content.title
                :title="$title"
                :heading="$heading"
                :background="$background_color"
                :contentItems="$content_items"
                :heading_size="$heading_size"
              />
              <x-content.text
                :content="$content"
                :background="$background_color"
                :contentItems="$content_items"
              />
              <x-content.buttons
                :buttons="$buttons"
                :contentItems="$content_items"
              />
            </div>
          </div>
        @endif

        @if ($items)
          <div class="space-y-4">
            <div data-reveal-group class="flex-shrink-0">
              <x-slider-nav
                :background="$background_color"
                prev-class="cta-slider-prev"
                next-class="cta-slider-next"
              />
            </div>
            <div data-reveal-group class="cta-slider swiper !overflow-visible" data-slider-type="cta">
              <div class="swiper-wrapper">
                @foreach ($items as $item)
                  <div class="swiper-slide !h-auto">
                    <x-cards.cta-card
                      :layout="$item['layout'] ?? ''"
                      :image="$item['image'] ?? null"
                      :background-color="$item['background_color'] ?? null"
                      :title="$item['title'] ?? null"
                      :type-content="$item['type_content'] ?? null"
                      :content="$item['content'] ?? null"
                      :links="$item['links'] ?? null"
                      :buttons="$item['buttons'] ?? null"
                    />
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
</x-section>
