<section class="scrollable-text-section relative my-4 mx-4 rounded-2xl md:rounded-3xl overflow-hidden" data-scrollable-text>
  <div class="scrollable-text-background absolute inset-0 w-full h-screen" data-scrollable-bg>
    @if($background_image)
      <div class="absolute inset-0 w-full h-full bg-gradient-to-l from-black/65 to-transparent z-10"></div>
      <div class="absolute inset-0 w-full h-full bg-black/50 z-10"></div>
      {!! wp_get_attachment_image($background_image['ID'], 'full', false, ['class' => 'w-full h-full absolute inset-0 object-cover']) !!}
    @else
      <div class="absolute inset-0 bg-dark"></div>
    @endif
  </div>

  <div class="scrollable-text-content relative z-20" data-scrollable-content>
    <div class="container">
      <div class="grid md:grid-cols-2 gap-8 lg:gap-16">
        <div class="relative">
          <div class="pt-16 md:pt-24 lg:pt-32 pb-16 md:pb-24" data-scrollable-title>
            <div class="flex flex-col gap-4" data-reveal-group>
              <x-content.subtitle
                :subtitle="$subtitle"
                background="image"
                :contentItems="$content_items"
              />
              <x-content.title
                :title="$title"
                :heading="$heading"
                background="image"
                :contentItems="$content_items"
                class="text-3xl md:text-4xl lg:text-5xl xl:text-6xl leading-tight"
              />
            </div>
          </div>
        </div>

        <div class="flex flex-col pb-16 md:pb-24" data-scrollable-steps>
          @if($steps)
            @foreach($steps as $index => $step)
              <div class="step py-8 md:py-12 {{ $loop->first ? 'pt-16 md:pt-24 lg:pt-32' : '' }}" data-reveal-group>
                <div class="flex flex-col gap-3 md:gap-4 max-w-md">
                  <span class="inline-flex items-center justify-center size-10 md:size-12 rounded-md bg-dark/70 backdrop-blur-lg border border-white/7 text-primary text-sm font-medium">
                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                  </span>
                  @if($step['title'])
                    <h3 class="text-white text-2xl md:text-3xl lg:text-4xl font-semibold leading-tight">
                      {!! $step['title'] !!}
                    </h3>
                  @endif
                  @if($step['content'])
                    <div class="text-white text-base md:text-lg leading-relaxed prose prose-invert">
                      {!! $step['content'] !!}
                    </div>
                  @endif
                  @if(!empty($step['link']))
                    <div class="mt-4 flex items-start">
                      <x-content.button
                        :href="$step['link']['url']"
                        type="primary"
                        :title="$step['link']['title']"
                        :target="$step['link']['target'] ?? '_self'"
                      />
                    </div>
                  @endif
                </div>
              </div>
            @endforeach
          @endif
        </div>
      </div>
    </div>
  </div>
</section>
