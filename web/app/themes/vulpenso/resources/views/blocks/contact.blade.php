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
    <div class="container relative z-10">
      @php
        $hasContactButtons = $contact_buttons && count($contact_buttons) > 0;
      @endphp
      <div @class([
        'grid gap-4 md:gap-8 lg:gap-12',
        'lg:grid-cols-2' => !$hasContactButtons,
        'lg:grid-cols-5' => $hasContactButtons,
      ])>
        {{-- Left Column: Content + Contact Buttons --}}
        <div @class([
          'flex flex-col gap-6',
          'lg:col-span-3' => $hasContactButtons,
        ])>
          {{-- Content section --}}
            <div class="flex flex-col gap-4 md:gap-6 md:pr-12 lg:pr-20 xl:pr-24">
              <x-content.subtitle
                :subtitle="$subtitle"
                :contentItems="$content_items"
                :background="$background_color"
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
                <div class="flex flex-wrap items-center gap-4 mt-2">
                  <x-content.buttons :buttons="$buttons" />
                </div>
              @endif
            </div>

          {{-- Contact Buttons --}}
          @if($contact_buttons && count($contact_buttons) > 0)
            <div class="grid xl:grid-cols-2 gap-4 mt-4 md:mt-8 lg:mt-12">
              @foreach($contact_buttons as $button)
                <x-cards.link-card
                  :link="$button['link']"
                  :icon_url="$button['icon_url']"
                  :label="$button['label']"
                  :background="$background_color"
                />
              @endforeach
            </div>
          @endif
        </div>

        {{-- Right Column: Gravity Form --}}
        @if($gravity_form_id)
          <div @class([
            'rounded-xl lg:rounded-3xl p-6 md:p-8 lg:p-10',
            'lg:col-span-2' => $hasContactButtons,
            'border bg-white/3 border-white/7 form-dark' => $background_color === 'bg-dark',
            'bg-light form-light' => $background_color !== 'bg-dark',
          ])>
            {!! gravity_form($gravity_form_id, false, false, false, null, true, 0, false) !!}
          </div>
        @endif
      </div>
    </div>
  </div>
</x-section>
