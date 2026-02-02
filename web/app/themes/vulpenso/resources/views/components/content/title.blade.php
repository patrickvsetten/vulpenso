@props([
  'title',
  'heading' => 'h2',
  'headingWeight' => 'medium',
  'class' => '',
  'background' => '',
  'contentItems' => [],
  'split' => true,
])

@if ($title && (!filled($contentItems) || in_array('title', $contentItems)))
  <{{ $heading }}
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
