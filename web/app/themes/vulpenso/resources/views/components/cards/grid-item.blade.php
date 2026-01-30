@props([
    'item',
    'background_color' => 'bg-white',
])

@php
  $colSpan = match($item['width']) {
    '1/3' => 'col-span-6 md:col-span-3 lg:col-span-2',
    '1/2' => 'col-span-6 md:col-span-3',
    '2/3' => 'col-span-6 md:col-span-6 lg:col-span-4',
    default => 'col-span-6 md:col-span-3 lg:col-span-2',
  };
  $hasImage = !empty($item['image']);
  $iconSrc = $item['icon_url'] ?? null;
@endphp

<a
  href="{{ $item['link'] }}"
  @if(isset($item['link_target']) && $item['link_target']) target="{{ $item['link_target'] }}" @endif
  @class([
    $colSpan,
    'group rounded-xl md:rounded-3xl flex flex-col border overflow-hidden relative min-h-[14rem] lg:min-h-[24rem]',
    'bg-dark/3 border-dark/7 text-dark' => $background_color === 'bg-white',
    'bg-white/3 border-white/7 text-white' => $background_color !== 'bg-white',
  ])
>
  @if($hasImage)
    <div class="absolute inset-0 w-full h-full opacity-0 group-hover:opacity-100 transition-opacity duration-300 will-change-[opacity] [transform:translate3d(0,0,0)]">
      @if(is_array($item['image']))
        {!! wp_get_attachment_image($item['image']['ID'], 'large', false, ['class' => 'absolute inset-0 w-full h-full object-cover transition-transform duration-300 group-hover:scale-105']) !!}
      @else
        {!! wp_get_attachment_image($item['image'], 'large', false, ['class' => 'absolute inset-0 w-full h-full object-cover transition-transform duration-300 group-hover:scale-105']) !!}
      @endif
      <div class="bg-black/15 absolute inset-0 z-10"></div>
      <div class="bg-gradient-to-t from-black/80 to-transparent absolute inset-0 z-10"></div>
    </div>
  @endif

  <div class="relative z-20 p-8 md:p-10 lg:p-12 flex flex-col h-full">
    <div class="flex w-full justify-between">
      <div class="flex items-center">
        @if($iconSrc)
          <div class="size-12 bg-white/10 backdrop-blur-xs rounded-lg grid place-items-center relative overflow-clip transition-[background-color] duration-300 group-hover:bg-dark/50 isolate will-change-transform backface-hidden [transform:translate3d(0,0,0)]">
            <x-lordicon
              :src="$iconSrc"
              trigger="hover"
              target=".group"
              stroke=""
              class="icon-lottie size-6 flex-shrink-0 flex-grow-0 [transform:translate3d(0,0,0)]"
              colors="primary:#FFFFFF,secondary:#FFFFFF">
            </x-lordicon>
          </div>
        @endif
        <div class="bg-white/10 backdrop-blur-md rounded-lg px-4 py-2 h-12 flex items-center transition-[background-color] duration-300 group-hover:bg-dark/50 isolate will-change-transform backface-hidden [transform:translate3d(0,0,0)]">
          <p @class([
            'font-semibold text-base',
            'group-hover:text-white' => $hasImage,
          ])>
            {!! $item['title'] !!}
          </p>
        </div>
      </div>
      <x-arrow-button type="outline-to-white" />
    </div>
    @if($item['short_description'])
      <h3 class="mt-auto font-medium text-lg md:text-xl lg:text-2xl max-w-md pr-8 line-clamp-3 transition-colors duration-300 text-white">
        {!! $item['short_description'] !!}
      </h3>
    @endif
  </div>
</a>
