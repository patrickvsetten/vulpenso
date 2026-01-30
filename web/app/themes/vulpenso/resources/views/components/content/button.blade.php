@props([
    'type' => 'primary',
    'target' => '_self',
    'class' => '',
    'href' => '',
    'title' => '',
    'icon' => 'arrow',
])

@php
  $element = $href ? 'a' : 'button';
@endphp

<{{ $element }}
  {{ $attributes->class([
    'btn-icon group flex items-center gap-2 text-base leading-tight no-underline',
    $class => $class,
  ]) }}
  data-theme="{{ $type }}"
  data-icon="{{ $icon }}"
  @if($href)
  href="{{ $href }}"
  target="{{ $target }}"
  @endif
>
  <div class="btn-icon__content flex items-center gap-4 rounded-lg px-4 py-2 md:py-3 relative overflow-hidden">
    <div class="btn-icon__mask relative z-10 flex items-center overflow-hidden">
      <span class="btn-icon__text text-base lg:text-lg font-medium whitespace-nowrap">{!! $title ?: $slot !!}</span>
    </div>
    <div class="btn-icon__icon relative z-10 flex-none flex justify-center items-center">
      <div class="btn-icon__icon-bg absolute w-full h-full rounded-md"></div>
      @if($icon === 'plus')
        {{-- Plus icon (static, rotates to X on hover) --}}
        <div class="btn-icon__icon-wrap btn-icon__icon-wrap--plus relative flex justify-center items-center w-full h-full">
          <svg viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg" class="btn-icon__plus size-3.5">
            <path d="M7 1V13M13 7H1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
      @else
        {{-- Arrow icon (slides through on hover) --}}
        <div class="btn-icon__icon-wrap relative flex justify-end items-center w-full h-full overflow-hidden">
          <div class="btn-icon__icon-list flex-none flex items-center h-full">
            <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="btn-icon__arrow flex-none h-full p-1">
              <path d="M11.6666 28.3332L28.3333 11.6665M28.3333 11.6665H13.3333M28.3333 11.6665V26.6665" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="btn-icon__arrow flex-none h-full p-1">
              <path d="M11.6666 28.3332L28.3333 11.6665M28.3333 11.6665H13.3333M28.3333 11.6665V26.6665" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="btn-icon__arrow flex-none h-full p-1">
              <path d="M11.6666 28.3332L28.3333 11.6665M28.3333 11.6665H13.3333M28.3333 11.6665V26.6665" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
        </div>
      @endif
    </div>
    <div class="btn-icon__bg absolute inset-0 z-0"></div>
  </div>
</{{ $element }}>
