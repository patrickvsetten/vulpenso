@props([
  'subtitle',
  'contentItems' => [],
  'background' => '',
  'type' => 'basic',
  'class' => '',
])

@if ($subtitle && (!filled($contentItems) || in_array('subtitle', $contentItems)))
  <div class="flex items-start">
    @if($type === 'basic')
      <p
        @class([
          'uppercase text-lg',
          'text-white' => in_array($background, ['bg-secondary', 'bg-primary', 'image']),
          'text-dark' => in_array($background, ['bg-light', 'bg-white']),
          $class,
        ])
      >
          {!! $subtitle !!}
      </p>
    @else
      <p
        @class([
          'uppercase text-sm px-2 md:px-3 py-1 border border-current/10 text-current font-body-bold tracking-wide',
          'text-white' => in_array($background, ['bg-secondary', 'bg-primary', 'image']),
          'text-dark' => in_array($background, ['bg-light', 'bg-white']),
          $class,
        ])
      >
          {!! $subtitle !!}
      </p>
    @endif
  </div>
@endif
