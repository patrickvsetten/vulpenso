@props([
    'usp',
    'background_color' => '',
])

@php
  $iconSrc = $usp['icon_url'] ?? null;
@endphp

<div
  @class([
    'group rounded-xl md:rounded-3xl flex flex-col border p-8 md:p-10 lg:p-12',
    'bg-dark/3 border-dark/7 text-dark' => $background_color !== 'bg-dark',
    'bg-white/3 border-white/7 text-white' => $background_color === 'bg-dark',
  ])
>
  {{-- Icon --}}
  @if($iconSrc)
    <div @class([
      'size-16 rounded-lg grid place-items-center mb-8',
      'bg-dark/5' => $background_color !== 'bg-dark',
      'bg-primary/10' => $background_color === 'bg-dark',
    ])>
      <x-lordicon
        :src="$iconSrc"
        trigger="hover"
        target=".group"
        stroke=""
        @class([
          'icon-lottie size-8 flex-shrink-0 flex-grow-0',
        ])
        :colors="$background_color === 'bg-white' ? 'primary:#1a1a1a,secondary:#1a1a1a' : 'primary:#C38E66,secondary:#C38E66'"
      />
    </div>
  @endif

  {{-- Title --}}
  @if($usp['title'])
    <h3 @class([
      'font-semibold text-lg md:text-xl lg:text-2xl mb-4',
      'text-dark' => $background_color === 'bg-white',
      'text-white' => $background_color !== 'bg-white',
    ])>
      {!! $usp['title'] !!}
    </h3>
  @endif

  {{-- Content --}}
  @if($usp['content'])
    <div @class([
      'prose lg:prose-lg',
      'text-dark' => $background_color === 'bg-white',
      'text-white' => $background_color !== 'bg-white',
    ])>
      {!! $usp['content'] !!}
    </div>
  @endif

  @if($usp['link'])
    <div class="flex items-start mt-4 md:mt-6">
      <x-content.button
        type="primary"
        :href="$usp['link']['url']"
        :title="$usp['link']['title']"
        :target="$usp['link']['target'] ?? '_self'"
      />
    </div>
  @endif
</div>
