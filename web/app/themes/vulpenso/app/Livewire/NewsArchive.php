<?php

namespace App\Livewire;

use Livewire\Component;

class NewsArchive extends Component
{
    public string $search = '';
    public ?string $activeCategory = null;
    public int $perPage = 9;
    public int $currentPage = 1;

    protected $queryString = [
        'search' => ['except' => ''],
        'activeCategory' => ['except' => null, 'as' => 'categorie'],
        'currentPage' => ['except' => 1, 'as' => 'pagina'],
    ];

    public function updatingSearch(): void
    {
        $this->currentPage = 1;
    }

    public function updatingActiveCategory(): void
    {
        $this->currentPage = 1;
    }

    public function setCategory(?string $categorySlug): void
    {
        $this->activeCategory = $categorySlug;
        $this->currentPage = 1;
    }

    public function clearFilters(): void
    {
        $this->activeCategory = null;
        $this->currentPage = 1;
    }

    public function previousPage(): void
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
        }
    }

    public function nextPage(): void
    {
        if ($this->currentPage < $this->newsPosts['totalPages']) {
            $this->currentPage++;
        }
    }

    public function gotoPage(int $page): void
    {
        $this->currentPage = $page;
    }

    public function getCategoriesProperty(): array
    {
        $terms = get_terms([
            'taxonomy' => 'news_category',
            'hide_empty' => true,
            'orderby' => 'name',
            'order' => 'ASC',
        ]);

        if (is_wp_error($terms) || empty($terms)) {
            return [];
        }

        return array_map(fn($term) => [
            'id' => $term->term_id,
            'name' => $term->name,
            'slug' => $term->slug,
            'count' => $term->count,
        ], $terms);
    }

    public function getNewsPostsProperty(): array
    {
        $args = [
            'post_type' => 'news',
            'posts_per_page' => $this->perPage,
            'paged' => $this->currentPage,
            'orderby' => 'date',
            'order' => 'DESC',
            'post_status' => 'publish',
        ];

        // Search filter
        if (!empty($this->search)) {
            $args['s'] = $this->search;
        }

        // Category filter
        if ($this->activeCategory) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'news_category',
                    'field' => 'slug',
                    'terms' => $this->activeCategory,
                ],
            ];
        }

        $query = new \WP_Query($args);

        return [
            'posts' => $this->formatPosts($query->posts),
            'total' => $query->found_posts,
            'totalPages' => $query->max_num_pages,
            'currentPage' => $this->currentPage,
        ];
    }

    protected function formatPosts(array $posts): array
    {
        return array_map(function ($post) {
            $wordCount = str_word_count(strip_tags($post->post_content));
            $readingTime = max(1, (int) ceil($wordCount / 200));
            $categories = get_the_terms($post->ID, 'news_category');

            return [
                'id' => $post->ID,
                'title' => $post->post_title,
                'url' => get_permalink($post->ID),
                'thumbnail' => get_post_thumbnail_ID($post->ID),
                'categories' => $categories && !is_wp_error($categories) ? $categories : [],
                'reading_time' => $readingTime,
                'excerpt' => get_the_excerpt($post->ID),
                'date' => get_the_date('j F Y', $post->ID),
            ];
        }, $posts);
    }

    public function getTotalPostsProperty(): int
    {
        return $this->newsPosts['total'];
    }

    public function getHasFiltersProperty(): bool
    {
        return $this->activeCategory !== null;
    }

    public function render()
    {
        $newsData = $this->newsPosts;

        return view('livewire.news-archive', [
            'posts' => $newsData['posts'],
            'categories' => $this->categories,
            'totalPosts' => $newsData['total'],
            'totalPages' => $newsData['totalPages'],
            'currentPage' => $newsData['currentPage'],
            'hasFilters' => $this->hasFilters,
        ]);
    }
}
