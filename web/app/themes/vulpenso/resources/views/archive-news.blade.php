@extends('layouts.app')

@section('content')
  {{-- Hero Section --}}
  <x-section
    pt="pt-0"
    pb="pb-0"
  >
    <div class="container">
      <div class="flex justify-center">
        <x-content.title
          title="Ons laatste nieuws"
          heading="h1"
          background="bg-dark"
        />
      </div>
    </div>
  </x-section>

  {{-- News Archive --}}
  <x-section pt="pt-0">
    <div class="container">
      <livewire:news-archive />
    </div>
  </x-section>
@endsection
