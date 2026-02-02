<div>
  {{-- Filter Bar --}}
  <div class="flex mt-12">
    <div class="inline-flex items-center gap-2 p-4 bg-white/3 border-white/7 rounded-xl text-white">
        <button
          wire:click="setCategory(null)"
          @class([
            'px-5 py-3 rounded-lg font-bold text-sm transition-all duration-300 cursor-pointer',
            'bg-primary text-white' => $activeCategory === null,
            'bg-primary/10 text-white hover:bg-white hover:text-primary' => $activeCategory !== null,
          ])
        >
          Alles
        </button>

        @foreach($categories as $category)
          <button
            wire:click="setCategory('{{ $category['slug'] }}')"
            @class([
              'px-5 py-3 rounded-lg font-bold text-sm transition-all duration-300 cursor-pointer',
              'bg-primary text-white' => $activeCategory === $category['slug'],
              'bg-primary/10 text-white hover:bg-white hover:text-primary' => $activeCategory !== $category['slug'],
            ])
          >
            {!! $category['name'] !!}
          </button>
        @endforeach
    </div>
  </div>

  {{-- News Grid --}}
  <div
    class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8 md:mt-12 lg:mt-16"
    wire:loading.class="opacity-50"
  >
    @forelse($posts as $post)
      <x-cards.news
        :title="$post['title']"
        :permalink="$post['url']"
        :thumbnail="$post['thumbnail']"
        :date="$post['date']"
        :category="!empty($post['categories']) ? $post['categories'][0]->name : ''"
        wire:key="news-{{ $post['id'] }}"
      />
    @empty
      <div class="col-span-full text-center py-12">
        <div class="max-w-md mx-auto">
          <svg class="w-16 h-16 mx-auto text-white/30 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
          </svg>
          <p class="text-white/60 text-lg mb-2">Geen nieuwsberichten gevonden.</p>
          @if($hasFilters)
            <p class="text-white/40 text-sm">Probeer een andere categorie.</p>
          @endif
        </div>
      </div>
    @endforelse
  </div>

  {{-- Pagination --}}
  @if($totalPages > 1)
    <div class="mt-12 flex justify-center">
      <nav class="flex items-center gap-2">
        {{-- Previous Button --}}
        <button
          wire:click="previousPage"
          @disabled($currentPage === 1)
          @class([
            'w-12 h-12 flex items-center justify-center rounded-lg font-bold transition-all duration-300',
            'bg-primary text-white/30 cursor-not-allowed' => $currentPage === 1,
            'bg-primary text-white hover:bg-primary cursor-pointer' => $currentPage !== 1,
          ])
        >
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>

        {{-- Page Numbers --}}
        @php
          $start = max(1, $currentPage - 2);
          $end = min($totalPages, $currentPage + 2);
        @endphp

        @if($start > 1)
          <button
            wire:click="gotoPage(1)"
            class="w-12 h-12 flex items-center justify-center rounded-lg font-bold bg-primary-dark text-white hover:bg-yellow hover:text-primary transition-all duration-300 cursor-pointer"
          >
            1
          </button>
          @if($start > 2)
            <span class="text-white/40 px-2">...</span>
          @endif
        @endif

        @for($i = $start; $i <= $end; $i++)
          <button
            wire:click="gotoPage({{ $i }})"
            @class([
              'w-12 h-12 flex items-center justify-center rounded-lg font-bold transition-all duration-300 cursor-pointer',
              'bg-yellow text-primary' => $currentPage === $i,
              'bg-primary-dark text-white hover:bg-yellow hover:text-primary' => $currentPage !== $i,
            ])
          >
            {{ $i }}
          </button>
        @endfor

        @if($end < $totalPages)
          @if($end < $totalPages - 1)
            <span class="text-white/40 px-2">...</span>
          @endif
          <button
            wire:click="gotoPage({{ $totalPages }})"
            class="w-12 h-12 flex items-center justify-center rounded-lg font-bold bg-primary-dark text-white hover:bg-yellow hover:text-primary transition-all duration-300 cursor-pointer"
          >
            {{ $totalPages }}
          </button>
        @endif

        {{-- Next Button --}}
        <button
          wire:click="nextPage"
          @disabled($currentPage === $totalPages)
          @class([
            'w-12 h-12 flex items-center justify-center rounded-lg font-bold transition-all duration-300',
            'bg-primary-dark text-white/30 cursor-not-allowed' => $currentPage === $totalPages,
            'bg-primary-dark text-white hover:bg-yellow hover:text-primary cursor-pointer' => $currentPage !== $totalPages,
          ])
        >
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>
      </nav>
    </div>
  @endif

  {{-- Loading Indicator --}}
  <div wire:loading class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2  text-primary px-4 py-2 rounded-lg shadow-lg font-medium z-50">
    Laden...
  </div>
</div>
