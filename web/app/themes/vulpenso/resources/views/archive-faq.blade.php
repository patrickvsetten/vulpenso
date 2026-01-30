@extends('layouts.app')

@section('content')
  {{-- Hero Section --}}
  <x-section class="">
    <div class="container">
      <x-content.subtitle class="block w-full mb-2" subtitle="Hulp nodig?" />
      <x-content.title
        title="Veelgestelde vragen"
        heading="h1"
        background="bg-primary"
      />
      <p class="text-white/70 text-lg mt-6">
        Vind snel antwoord op je vragen over Business Park Soest, onze bedrijfsunits en services.
      </p>
    </div>
  </x-section>

  {{-- FAQ Archive Livewire Component --}}
  <x-section pt="pt-0">
    <div class="container">
      <livewire:faq-archive />
    </div>
  </x-section>

  {{-- CTA Section --}}
  <x-section bg="bg-primary-dark">
    <div class="container">
      <div class="max-w-3xl mx-auto text-center">
        <h2 class="text-white text-3xl md:text-4xl font-bold mb-4">
          Staat je vraag er niet tussen?
        </h2>
        <p class="text-white/60 text-lg mb-8">
          Geen probleem! Neem contact met ons op en we helpen je graag verder.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <a
            href="/contact"
            class="inline-flex items-center justify-center gap-2 bg-yellow text-primary px-8 py-4 rounded-xl font-bold hover:bg-yellow/90 transition-colors"
          >
            Contact opnemen
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
          </a>
          <a
            href="tel:+31355431090"
            class="inline-flex items-center justify-center gap-2 bg-white/10 text-white px-8 py-4 rounded-xl font-bold hover:bg-white/20 transition-colors"
          >
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
            </svg>
            Bel direct
          </a>
        </div>
      </div>
    </div>
  </x-section>
@endsection
