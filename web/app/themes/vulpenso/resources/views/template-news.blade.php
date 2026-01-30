{{--
  Template Name: Nieuws Template
--}}

@extends('layouts.app')

@section('content')
  {{-- Hero Section --}}
  <section class="relative py-0 mt-4 overflow-clip mx-4 rounded-2xl md:rounded-3xl">
    <div class="container relative z-20">
      <div class="pb-28 pt-48 md:pb-40 md:pt-32 lg:pt-40 xl:pb-48 xl:pt-56 2xl:pb-56 2xl:pt-64 w-full text-center max-w-2xl mx-auto">
        <x-content.subtitle
          subtitle="Blijf op de hoogte"
          background="image"
          class="block w-full mb-4"
        />
        <x-content.title
          :title="get_the_title()"
          heading="h1"
          headingWeight="light"
          background="image"
          class="leading-[1.25] text-3xl md:text-4xl lg:text-5xl xl:text-6xl"
        />
      </div>
    </div>
    <div class="absolute inset-0 z-0">
      <div class="absolute inset-0 w-full h-full bg-black/[0.45] z-10"></div>
      @if(has_post_thumbnail())
        {!! get_the_post_thumbnail(null, 'full', ['class' => 'w-full h-full absolute inset-0 object-cover']) !!}
      @else
        <div class="absolute inset-0 bg-dark"></div>
      @endif
    </div>
  </section>

  {{-- News Archive Livewire Component --}}
  <x-section pt="pt-0">
    <div class="container">
      <livewire:news-archive />
    </div>
  </x-section>
@endsection
