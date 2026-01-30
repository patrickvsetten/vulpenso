{{--
  Template Name: Container Template
--}}

@extends('layouts.app')

@section('content')

@php
  global $post;
  wp_reset_postdata()
@endphp

<header class="relative overflow-x-clip">
  <div class="container relative z-10 py-8 md:py-12 lg:py-16 xl:py-20">
      <div class="grid lg:grid-cols-2 gap-8 lg:gap-32">
          <div class="flex items-center">
              <div class="w-full max-w-xl grid gap-2">
                  <h1 class="mb-0">{!! get_the_title() !!}</h1>
              </div>
          </div>
      </div>
  </div>
</header>

<section class="bg-offwhite pt-12 md:pt-16 lg:pt-20">
  <div class="container">
    <div class="prose mx-auto lg:prose-lg">
        @php the_content() @endphp
    </div>
  </div>
</section>

@endsection
