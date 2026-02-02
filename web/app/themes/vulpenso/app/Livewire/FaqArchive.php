<?php

namespace App\Livewire;

use Livewire\Component;

class FaqArchive extends Component
{
    public string $search = '';
    public ?string $activeCategory = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'activeCategory' => ['except' => null, 'as' => 'categorie'],
    ];

    public function updatingSearch(): void
    {
        $this->activeCategory = null;
    }

    public function setCategory(?string $slug): void
    {
        $this->activeCategory = $slug;
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->activeCategory = null;
    }

    public function getCategoriesProperty(): array
    {
        $terms = get_terms([
            'taxonomy' => 'faq-category',
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

    public function getGroupedFaqsProperty(): array
    {
        $categories = $this->categories;
        $grouped = [];
        $searchTerm = strtolower(trim($this->search));

        foreach ($categories as $category) {
            // Skip categories that don't match filter
            if ($this->activeCategory && $category['slug'] !== $this->activeCategory) {
                continue;
            }

            $args = [
                'post_type' => 'faq',
                'posts_per_page' => -1,
                'orderby' => 'menu_order',
                'order' => 'ASC',
                'post_status' => 'publish',
                'tax_query' => [
                    [
                        'taxonomy' => 'faq-category',
                        'field' => 'term_id',
                        'terms' => $category['id'],
                    ],
                ],
            ];

            // Add search filter
            if (!empty($searchTerm)) {
                $args['s'] = $this->search;
            }

            $query = new \WP_Query($args);
            $faqs = $this->formatFaqs($query->posts, $searchTerm);

            // Only include category if it has FAQs (especially important for search)
            if (!empty($faqs)) {
                $grouped[] = [
                    'category' => $category,
                    'faqs' => $faqs,
                ];
            }
        }

        // Also get uncategorized FAQs if not filtering by category
        if (!$this->activeCategory) {
            $uncategorizedArgs = [
                'post_type' => 'faq',
                'posts_per_page' => -1,
                'orderby' => 'menu_order',
                'order' => 'ASC',
                'post_status' => 'publish',
                'tax_query' => [
                    [
                        'taxonomy' => 'faq-category',
                        'operator' => 'NOT EXISTS',
                    ],
                ],
            ];

            if (!empty($searchTerm)) {
                $uncategorizedArgs['s'] = $this->search;
            }

            $uncategorizedQuery = new \WP_Query($uncategorizedArgs);
            $uncategorizedFaqs = $this->formatFaqs($uncategorizedQuery->posts, $searchTerm);

            if (!empty($uncategorizedFaqs)) {
                $grouped[] = [
                    'category' => [
                        'id' => 0,
                        'name' => 'Overig',
                        'slug' => 'overig',
                        'count' => count($uncategorizedFaqs),
                    ],
                    'faqs' => $uncategorizedFaqs,
                ];
            }
        }

        return $grouped;
    }

    protected function formatFaqs(array $posts, string $searchTerm = ''): array
    {
        return array_map(function ($post) use ($searchTerm) {
            $title = apply_filters('the_title', $post->post_title);
            $content = apply_filters('the_content', $post->post_content);

            // Highlight search terms if searching
            if (!empty($searchTerm)) {
                $title = $this->highlightSearchTerm($title, $searchTerm);
                $content = $this->highlightSearchTerm($content, $searchTerm);
            }

            return [
                'id' => $post->ID,
                'title' => $title,
                'content' => $content,
            ];
        }, $posts);
    }

    protected function highlightSearchTerm(string $text, string $searchTerm): string
    {
        if (empty($searchTerm)) {
            return $text;
        }

        // Escape special regex characters
        $escapedSearch = preg_quote($searchTerm, '/');

        // Replace search term with highlighted version (case-insensitive)
        return preg_replace(
            '/(' . $escapedSearch . ')/i',
            '<mark class="bg-yellow/50 text-primary px-0.5 rounded">$1</mark>',
            $text
        );
    }

    public function getTotalFaqsProperty(): int
    {
        $count = 0;
        foreach ($this->groupedFaqs as $group) {
            $count += count($group['faqs']);
        }
        return $count;
    }

    public function getHasFiltersProperty(): bool
    {
        return !empty($this->search) || $this->activeCategory !== null;
    }

    public function render()
    {
        return view('livewire.faq-archive', [
            'groupedFaqs' => $this->groupedFaqs,
            'categories' => $this->categories,
            'totalFaqs' => $this->totalFaqs,
            'hasFilters' => $this->hasFilters,
        ]);
    }
}
