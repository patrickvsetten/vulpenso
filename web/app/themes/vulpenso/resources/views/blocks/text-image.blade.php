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
      <div class="grid md:grid-cols-2 gap-4 lg:gap-6 xl:gap-8 md:items-center">
        <div @class([
            'h-full md:p-12 lg:p-16 flex items-center' => $background_color !== 'bg-dark',
            $text_position,
          ])
          data-reveal-group
        >
          <div class="flex flex-col gap-4 md:gap-6">
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
              class="max-w-xl"
            />
            <x-content.text
              :content="$content"
              :background="$background_color"
              :contentItems="$content_items"
              class="xl:max-w-xl"
            />
            @if ($add_links === 'yes' && $links)
              <div class="flex flex-col gap-4 mt-4 max-w-md">
                @foreach ($links as $link)
                  <x-cards.link-card
                    :link="$link['link']"
                    :icon_url="$link['icon_url']"
                    :label="$link['label']"
                    :background="$background_color"
                  />
                @endforeach
              </div>
            @endif
            <div class="flex items-center justify-start gap-4">
              <x-content.buttons
                :buttons="$buttons"
                :contentItems="$content_items"
                :background="$background_color"
              />
            </div>
        </div>
      </div>
      @if ($media)
        <div class="{{ $media_position }} relative z-10" data-reveal-group>
          <div class="isolate w-full {{ $show_highlight_overlay ? 'aspect-[16/18] md:aspect-[16/14]' : 'aspect-[16/14]' }} overflow-hidden relative rounded-2xl">
            <div>
              <x-content.media
                :media_type="$media['media_type']"
                :image="$media['image']"
                :video_layout="$media['video_layout']"
                :video_type="$media['video_type']"
                :video_link="$media['video_link']"
                :video_file="$media['video_file']"
                :placeholder="$media['placeholder']"
                :image_fit="$image_fit"
              />
            </div>

            @if ($show_highlight_overlay)
              <div class="absolute rounded-2xl bottom-2 md:bottom-4 left-2 md:left-auto right-2 md:right-4 p-4 md:p-8 bg-dark/70 backdrop-blur-lg border border-white/7 max-w-xs md:max-w-sm isolate will-change-transform [transform:translate3d(0,0,0)]" data-reveal-group>
                @if ($overlay_title)
                  <h3 class="text-primary text-lg md:text-xl font-semibold mb-2">{{ $overlay_title }}</h3>
                @endif
                @if ($overlay_text)
                  <p class="text-white/90 text-sm md:text-base mb-4">{!! $overlay_text !!}</p>
                @endif
                @if ($overlay_link)
                  <div class="flex items-start">
                    <x-content.button
                      type="white"
                      :href="$overlay_link['url']"
                      :target="$overlay_link['target'] ?? '_self'"
                      :title="$overlay_link['title']"
                    />
                  </div>
                @endif
              </div>
            @endif
          </div>
        </div>
      @endif
    </div>
  </div>
</x-section>
