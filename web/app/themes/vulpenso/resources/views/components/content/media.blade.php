@props([
  'media_type',
  'image' => '',
  'video_type' => '',
  'video_link' => '',
  'video_file' => '',
  'video_layout' => '',
  'placeholder' => '',
  'image_fit' => 'cover',
])

@php
  $objectFitClass = $image_fit === 'contain' ? 'object-contain' : 'object-cover';
@endphp

@if($media_type === 'image' && $image)
  <div
    @if($image_fit !== 'contain')
      data-scroll
      data-scroll-speed="-0.05"
    @endif
    class="absolute inset-0 w-full h-full z-0"
  >
    {!! wp_get_attachment_image( $image['ID'], 'full', "", ["class" => "absolute inset-0 w-full " . ($image_fit === 'contain' ? 'h-full' : 'h-[calc(100%+10vh)] mt-[-5vh]') . " " . $objectFitClass] ) !!}
  </div>
@elseif($media_type === 'video')
  <x-content.video 
    :video_type="$video_type"
    :video_link="$video_link"
    :video_file="$video_file"
    :video_layout="$video_layout"
    :placeholder="$placeholder"
  />
@endif