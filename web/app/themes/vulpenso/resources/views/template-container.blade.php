{{--
  Template Name: Container Template
--}}

@extends('layouts.app')

@section('content')

@php
  global $post;
  wp_reset_postdata()
@endphp

<x-section>
  <div class="container">
    <div class="prose prose-invert prose-lg max-w-none prose-headings:text-light prose-headings:font-semibold prose-p:text-light prose-a:text-yellow prose-a:no-underline hover:prose-a:underline prose-strong:text-light prose-ul:text-light prose-ol:text-light prose-li:marker:text-yellow prose-img:rounded-2xl prose-img:my-8">
      <h1 class="mb-0">{!! get_the_title() !!}</h1>
      @php the_content() @endphp
    </div>
  </div>
</x-section>

@endsection
