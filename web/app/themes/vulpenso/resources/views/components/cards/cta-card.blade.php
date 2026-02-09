@props([
    'layout' => 'content', // content || image-above-content || content-over-image
    'image' => null,
    'backgroundColor' => '',
    'title' => null,
    'typeContent' => null, // text || list
    'content' => null,
    'links' => [],
    'buttons' => [],
	  'showFlower' => false,
])

<div @class([
	'cta-card grid h-full group/card relative rounded-2xl md:rounded-3xl rounded-2xl',
	'content-start p-4' => $layout === 'image-above-content',
  'text-dark' => $backgroundColor === 'bg-light' || $backgroundColor === 'bg-white',
	'border bg-white/3 text-white border-white/7' => $backgroundColor === 'bg-dark',
  $backgroundColor => $backgroundColor !== 'bg-dark',
])>

	@if($layout === 'image-above-content' && $image && !empty($image['ID']))
    <div class="relative overflow-hidden aspect-[4/3] rounded-xl">
      <div
        data-scroll
        data-scroll-speed="-0.05"
        class="absolute inset-0 w-full h-full z-0"
      >
			  <img src="{{ wp_get_attachment_image_url($image['ID'], 'full') }}" alt="{{ get_post_meta($image['ID'], '_wp_attachment_image_alt', true) }}" class="absolute inset-0 w-full h-full md:h-[calc(100%+10vh)] md:mt-[-5vh] object-cover" loading="lazy">
      </div>
		</div>
	@endif

	<div class="relative z-20 p-6 md:py-6 xl:p-8 flex flex-col justify-end gap-4 md:gap-8 rounded-xl md:rounded-2xl overflow-clip">


	  @if($layout === 'content-over-image' && $image && !empty($image['ID']))
      <div class="absolute inset-0 z-10 bg-black/30"></div>
      <div class="absolute inset-0 z-10 bg-gradient-to-t from-black to-transparent"></div>
      <div
        data-scroll
        data-scroll-speed="-0.05"
        class="absolute inset-0 w-full h-full z-0"
      >
        <img src="{{ wp_get_attachment_image_url($image['ID'], 'full') }}" alt="{{ get_post_meta($image['ID'], '_wp_attachment_image_alt', true) }}" class="absolute inset-0 w-full h-full md:h-[calc(100%+10vh)] md:mt-[-5vh] object-cover" loading="lazy">
      </div>
    @endif
		<div class="space-y-4 md:space-y-8 relative z-20">

			<x-content.title
				heading="h3"
				:title="$title"
				:background="$layout == 'content-over-image' ? 'image' : $backgroundColor"
				class="text-xl md:text-2xl lg:text-3xl"
			/>
			@if($typeContent == 'list')
				<div @class([
					"flex flex-col divide-y -mt-2 md:-my-4",
					"divide-white/10" => in_array($backgroundColor, ['bg-dark']),
					"divide-dark/10" => in_array($backgroundColor, ['bg-white', 'bg-light']),
				])>
					@foreach($links as $link)
						@if(empty($link['link']['url'])) @continue @endif
						<a
							href="{{ $link['link']['url'] }}" target="{{ $link['link']['target'] }}"
							@class([
								'group inline-flex items-center justify-between gap-4 py-3 md:py-4 text-sm lg:text-base font-bold leading-relaxed',
								'text-white' => $layout == 'content-over-image' || in_array($backgroundColor, ['bg-primary', 'bg-secondary']),
								'text-secondary' => $layout != 'content-over-image' && !in_array($backgroundColor, ['bg-primary', 'bg-secondary']),
							])
						>
							<span>{!! $link['link']['title'] !!}</span>
              <div>
                <x-arrow-button size="xs" type="outline" />
              </div>
						</a>

					@endforeach
				</div>
			@else
				<x-content.text
					:content="$content"
          class="relative z-20"
					:background="$layout == 'content-over-image' ? 'image' : $backgroundColor"
				/>
			@endif
		</div>
		@if($buttons)
			<div class="flex flex-col items-start gap-2 md:gap-4 relative z-20">
				@foreach($buttons as $button)
					@if(empty($button['link']['url'])) @continue @endif
					<x-content.button
						:href="$button['link']['url']"
						:color="$button['color']"
						:title="$button['link']['title']"
						:target="$button['link']['target']"
					/>
				@endforeach
			</div>
		@endif
	</div>
</div>
