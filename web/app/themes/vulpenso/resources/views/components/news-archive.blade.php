@props([
  'perPage' => 9,
])

@php
  $currentPage = max(1, (int) ($_GET['pagina'] ?? 1));
  $activeCategory = isset($_GET['categorie']) ? (int) $_GET['categorie'] : null;
  $baseUrl = get_post_type_archive_link('news');

  // Get categories
  $terms = get_terms([
    'taxonomy' => 'news_category',
    'hide_empty' => true,
    'orderby' => 'name',
    'order' => 'ASC',
  ]);
  $categories = (!is_wp_error($terms) && !empty($terms)) ? $terms : [];

  // Query posts
  $args = [
    'post_type' => 'news',
    'posts_per_page' => $perPage,
    'paged' => $currentPage,
    'orderby' => 'date',
    'order' => 'DESC',
    'post_status' => 'publish',
  ];

  if ($activeCategory) {
    $args['tax_query'] = [
      [
        'taxonomy' => 'news_category',
        'field' => 'term_id',
        'terms' => $activeCategory,
      ],
    ];
  }

  $query = new WP_Query($args);
  $posts = $query->posts;
  $totalPosts = $query->found_posts;
  $totalPages = $query->max_num_pages;

  // Helper function to build URL with params
  $buildUrl = function($params = []) use ($baseUrl, $activeCategory, $currentPage) {
    $query = [];

    $cat = $params['categorie'] ?? $activeCategory;
    if ($cat !== null) {
      $query['categorie'] = $cat;
    }

    $page = $params['pagina'] ?? $currentPage;
    if ($page > 1) {
      $query['pagina'] = $page;
    }

    return $baseUrl . (!empty($query) ? '?' . http_build_query($query) : '');
  };
@endphp

<div>
  {{-- Filter Bar --}}
  @if(count($categories) > 0)
    <div class="mb-8 md:mb-12">
      <div class="flex flex-wrap items-center gap-2">
        <a
          href="{{ $baseUrl }}"
          @class([
            'px-5 py-3 uppercase tracking-wider rounded-lg font-bold text-sm transition-all duration-300',
            'bg-yellow text-primary' => $activeCategory === null,
            'bg-primary-dark text-white hover:bg-white hover:text-primary' => $activeCategory !== null,
          ])
        >
          Alles
        </a>

        @foreach($categories as $category)
          <a
            href="{{ $buildUrl(['categorie' => $category->term_id, 'pagina' => 1]) }}"
            @class([
              'px-5 py-3 uppercase tracking-wider rounded-lg font-bold text-sm transition-all duration-300',
              'bg-yellow text-primary' => $activeCategory === $category->term_id,
              'bg-primary-dark text-white hover:bg-white hover:text-primary' => $activeCategory !== $category->term_id,
            ])
          >
            {!! $category->name !!} ({{ $category->count }})
          </a>
        @endforeach
      </div>

      {{-- Active Filters Summary --}}
      @if($activeCategory)
        <div class="mt-4 flex items-center gap-4">
          <span class="text-white/60 text-sm">
            {{ $totalPosts }} {{ $totalPosts === 1 ? 'resultaat' : 'resultaten' }} gevonden
          </span>
          <a
            href="{{ $baseUrl }}"
            class="text-yellow text-sm font-medium hover:underline"
          >
            Filter wissen
          </a>
        </div>
      @endif
    </div>
  @endif

  {{-- News Grid --}}
  <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($posts as $post)
      @php
        $postCategories = get_the_terms($post->ID, 'news_category');
        $firstCategory = ($postCategories && !is_wp_error($postCategories)) ? $postCategories[0]->name : '';
      @endphp
      <x-cards.news
        :title="$post->post_title"
        :permalink="get_permalink($post->ID)"
        :thumbnail="get_post_thumbnail_id($post->ID)"
        :date="get_the_date('j F Y', $post->ID)"
        :category="$firstCategory"
      />
    @empty
      <div class="col-span-full text-center py-12">
        <div class="max-w-md mx-auto">
          <svg class="w-16 h-16 mx-auto text-white/30 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
          </svg>
          <p class="text-white/60 text-lg mb-2">Geen nieuwsberichten gevonden.</p>
          @if($activeCategory)
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
        @if($currentPage > 1)
          <a
            href="{{ $buildUrl(['pagina' => $currentPage - 1]) }}"
            class="w-12 h-12 flex items-center justify-center rounded-lg font-bold bg-primary-dark text-white hover:bg-yellow hover:text-primary transition-all duration-300"
          >
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
          </a>
        @else
          <span class="w-12 h-12 flex items-center justify-center rounded-lg font-bold bg-primary-dark text-white/30 cursor-not-allowed">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
          </span>
        @endif

        {{-- Page Numbers --}}
        @php
          $start = max(1, $currentPage - 2);
          $end = min($totalPages, $currentPage + 2);
        @endphp

        @if($start > 1)
          <a
            href="{{ $buildUrl(['pagina' => 1]) }}"
            class="w-12 h-12 flex items-center justify-center rounded-lg font-bold bg-primary-dark text-white hover:bg-yellow hover:text-primary transition-all duration-300"
          >
            1
          </a>
          @if($start > 2)
            <span class="text-white/40 px-2">...</span>
          @endif
        @endif

        @for($i = $start; $i <= $end; $i++)
          <a
            href="{{ $buildUrl(['pagina' => $i]) }}"
            @class([
              'w-12 h-12 flex items-center justify-center rounded-lg font-bold transition-all duration-300',
              'bg-yellow text-primary' => $currentPage === $i,
              'bg-primary-dark text-white hover:bg-yellow hover:text-primary' => $currentPage !== $i,
            ])
          >
            {{ $i }}
          </a>
        @endfor

        @if($end < $totalPages)
          @if($end < $totalPages - 1)
            <span class="text-white/40 px-2">...</span>
          @endif
          <a
            href="{{ $buildUrl(['pagina' => $totalPages]) }}"
            class="w-12 h-12 flex items-center justify-center rounded-lg font-bold bg-primary-dark text-white hover:bg-yellow hover:text-primary transition-all duration-300"
          >
            {{ $totalPages }}
          </a>
        @endif

        {{-- Next Button --}}
        @if($currentPage < $totalPages)
          <a
            href="{{ $buildUrl(['pagina' => $currentPage + 1]) }}"
            class="w-12 h-12 flex items-center justify-center rounded-lg font-bold bg-primary-dark text-white hover:bg-yellow hover:text-primary transition-all duration-300"
          >
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </a>
        @else
          <span class="w-12 h-12 flex items-center justify-center rounded-lg font-bold bg-primary-dark text-white/30 cursor-not-allowed">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </span>
        @endif
      </nav>
    </div>
  @endif
</div>
