@props([
    'item',
    'index' => 0,
])

@php
  $hasImage = !empty($item['image']);
  $iconSrc = $item['icon_url'] ?? null;
@endphp

<a
  href="{{ $item['link'] }}"
  class="service-menu__grid-item group rounded-xl md:rounded-2xl flex flex-col border overflow-hidden relative min-h-[14rem] lg:min-h-[20rem] bg-white/3 border-white/7 text-white"
  data-index="{{ $index }}"
>
  @if($hasImage)
    <div class="absolute inset-0 w-full h-full [transform:translate3d(0,0,0)]">
      @if(is_array($item['image']))
        {!! wp_get_attachment_image($item['image']['ID'], 'large', false, ['class' => 'absolute inset-0 w-full h-full object-cover transition-transform duration-300 group-hover:scale-105']) !!}
      @else
        {!! wp_get_attachment_image($item['image'], 'large', false, ['class' => 'absolute inset-0 w-full h-full object-cover transition-transform duration-300 group-hover:scale-105']) !!}
      @endif
      <div class="bg-black/25 absolute inset-0 z-10"></div>
      <div class="bg-gradient-to-t from-black/90 to-transparent absolute inset-0 z-10"></div>
    </div>
  @endif

  <div class="relative z-20 p-4 md:p-6 flex flex-col h-full">
    {{-- Top row: icon + title + arrow --}}
    <div class="flex w-full justify-between">
      <div class="flex items-center">
        @if($iconSrc)
          <div class="size-10 lg:size-12 rounded-lg grid place-items-center relative overflow-clip transition-[background-color] duration-300 bg-dark/70 isolate will-change-transform backface-hidden [transform:translate3d(0,0,0)]">
            <x-lordicon
              :src="$iconSrc"
              trigger="hover"
              target=".group"
              stroke=""
              class="icon-lottie size-5 lg:size-6 flex-shrink-0 flex-grow-0 [transform:translate3d(0,0,0)]"
              colors="primary:#FFFFFF,secondary:#FFFFFF"
            />
          </div>
        @endif
      </div>
    </div>
    <div class="mt-auto">
      <h3 class="mt-auto font-medium mb-4 text-lg md:text-xl lg:text-2xl max-w-md pr-8 line-clamp-3 transition-colors duration-300 text-white">
        {!! $item['title'] !!}
      </h3>
      <x-arrow-button size="sm" type="outline-to-white" />
    </div>
  </div>
</a>
