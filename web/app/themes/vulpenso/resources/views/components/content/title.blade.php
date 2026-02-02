@props([
  'title',
  'heading' => 'h2',
  'headingWeight' => 'medium',
  'class' => '',
  'background' => '',
  'contentItems' => [],
  'split' => true,
  'textReveal' => false,
])

@if ($title && (!filled($contentItems) || in_array('title', $contentItems)))
  <{{ $heading }}
    @if($textReveal)
      data-text-reveal="{{ $textReveal === true ? 'chars' : $textReveal }}"
    @endif
    @class([
      $class,
      'text-white' => in_array($background, ['bg-dark', 'image']),
      'text-dark' => in_array($background, ['bg-light', 'bg-white', 'bg-primary']),
      'font-medium' => $headingWeight === 'medium',
      'font-light' => $headingWeight === 'light',
    ])>
    {!! $title !!}
  </{{ $heading }}>
@endif
