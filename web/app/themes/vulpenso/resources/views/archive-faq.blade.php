{{--
  Template Name: FAQ Template
--}}


@extends('layouts.app')

@section('content')

  {{-- Hero Section --}}
  <x-section
    pt="pt-0"
    pb="pb-0"
  >
    <div class="container">
      <div class="flex">
        <x-content.title
          title="Veel gestelde vragen"
          heading="h1"
          background="bg-dark"
        />
      </div>
    </div>
  </x-section>

  {{-- FAQ Archive Livewire Component --}}
  <x-section>
    <div class="container">
      <livewire:faq-archive />
    </div>
  </x-section>

@endsection
