@props([
  'link' => null,
  'icon_url' => null,
  'label' => null,
  'background' => 'bg-dark',
])

@if ($link)
  <a
    href="{{ $link['url'] }}"
    target="{{ $link['target'] ?? '_self' }}"
    @class([
      'group flex items-center gap-4 md:gap-6 rounded-2xl p-4 md:p-5 transition-all duration-300',
      'bg-white' => $background !== 'bg-white',
      'bg-light' => $background === 'bg-white',
    ])
  >
    @if($icon_url)
      <div @class([
        'size-14 rounded-xl grid place-items-center flex-shrink-0',
        'bg-light' => $background !== 'bg-white',
        'bg-white' => $background === 'bg-white',
      ])>
        <x-lordicon
          :src="$icon_url"
          trigger="hover"
          target=".group"
          class="size-8"
          primary="#C38E66"
          secondary="#C38E66"
        />
      </div>
    @endif
    <div class="flex-grow">
      @if($label)
        <p class="text-sm text-dark/50 transition group-hover:text-primary">{{ $label }}</p>
      @endif
      <p class="text-lg md:text-xl font-semibold transition text-dark group-hover:text-primary">{{ $link['title'] }}</p>
    </div>
    <x-arrow-button type="text" class="flex-shrink-0" />
  </a>
@endif
