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
  {!! wp_get_attachment_image( $image['ID'], 'full', "", ["class" => "absolute inset-0 w-full h-full " . $objectFitClass] ) !!}
@elseif($media_type === 'video')
  <x-content.video 
    :video_type="$video_type"
    :video_link="$video_link"
    :video_file="$video_file"
    :video_layout="$video_layout"
    :placeholder="$placeholder"
  />
@endif