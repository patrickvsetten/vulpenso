<x-section
  :id="$id"
  :pt="$pt"
  :pb="$pb"
>
  <div @class([
    $background_color => $background_color && $background_color !== 'bg-dark',
    'py-12 md:py-16 lg:py-20 xl:py-24 relative overflow-clip lg:mx-4 rounded-xl md:rounded-3xl' => $background_color !== 'bg-dark',
  ])> 
    <div class="container relative z-10">
      <div @class([
        'max-w-3xl mx-auto flex flex-col gap-4 items-center' => $text_layout === 'centered',
        'grid md:grid-cols-2 md:gap-12 lg:w-10/12 md:mx-auto' => $text_layout === 'title_next_to_text',
        'flex flex-col gap-4 md:w-10/12 lg:w-8/12 md:mx-auto' => $text_layout === 'two_cols',
        'flex flex-col gap-4 lg:w-10/12 md:mx-auto' => $text_layout === 'text_indent',
      ])>
        <div data-reveal-group class="flex flex-col gap-4">
          <x-content.subtitle
            :subtitle="$subtitle"
            :contentItems="$content_items"
            :background="$background_color"
          />
          <x-content.title
            :title="$title"
            :heading="$heading"
            :background="$background_color"
            :headingSize="$heading_size"
            :contentItems="$content_items"
            class="max-w-3xl"
          />
        </div>
        @if($content || $buttons)
            <div data-reveal-group @class([
              'lg:pl-16 xl:pl-24' => $text_layout === 'text_indent',
              'w-full flex flex-col gap-4 lg:gap-8',
            ])>
              <x-content.text
                :content="$content"
                :background="$background_color"
                :contentItems="$content_items"
                @class([
                  'w-full',
                  'columns-1 md:columns-2 md:gap-12 lg:gap-20 max-w-full' => $text_layout === 'two_cols',
                  'prose lg:prose-lg' => $text_layout === 'text_indent',
                ])
              />
              <div class="flex justify-start">
                <x-content.buttons
                  :buttons="$buttons"
                  :contentItems="$content_items"
                />
              </div>
          </div>
        @endif
      </div>
    </div>
  </div>
</x-section>
