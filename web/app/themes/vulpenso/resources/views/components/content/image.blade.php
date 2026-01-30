@props([
  'image' => '',
])

{!! wp_get_attachment_image( $image, isset($size), "", ["class" => "absolute inset-0 w-full h-full object-cover"] ) !!}