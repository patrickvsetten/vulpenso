@props([
  'class' => '',
  'content',
  'background' => '',
  'contentItems' => [],
])

@if ($content && (!filled($contentItems) || in_array('content', $contentItems)))
  <div
    @class([
      $class,
      'lg:prose-lg',
      'prose prose-p:text-dark prose-ul:text-dark prose-strong:text-dark prose-ol:text-dark' => in_array($background, ['bg-white', 'bg-primary', 'bg-light']),
      'prose prose-invert text-white prose-a:text-primary prose-a:!underline' => in_array($background, ['bg-dark', 'image']),
    ])
  >
    {!! $content !!}
  </div>
@endif
