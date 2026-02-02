<?php

namespace App\Blocks;

use StoutLogic\AcfBuilder\FieldsBuilder;

class Faq extends BaseBlock
{
    public $name = 'FAQ';
    public $description = 'Veelgestelde vragen.';
    public $category = 'formatting';
    public $keywords = [];
    public $post_types = [];
    public $parent = [];
    public $mode = 'edit';
    public $view = 'blocks.faq';

    public $supports = [
        'full_height' => false,
        'anchor' => false,
        'mode' => 'edit',
        'multiple' => true,
        'supports' => ['mode' => false],
        'jsx' => true,
    ];

    public function getFaq(): array
    {
        $input = get_field('input');

        if ($input === 'choose') {
            return $this->formatFaqPosts(get_field('questions') ?: []);
        }

        if ($input === 'category') {
            return $this->formatFaqPosts($this->getFaqByCategory());
        }

        if ($input === 'all') {
            return $this->getGroupedFaqs();
        }

        return $this->formatFaqPosts($this->getDefaultFaqPosts());
    }

    protected function getFaqByCategory(): array
    {
        $terms = get_field('categories');

        if (empty($terms)) {
            return $this->getDefaultFaqPosts();
        }

        return get_posts([
            'post_type'      => 'faq',
            'posts_per_page' => -1,
            'order'          => 'DESC',
            'tax_query'      => [
                [
                    'taxonomy' => 'faq-category',
                    'field'    => 'term_id',
                    'terms'    => $terms,
                    'operator' => 'IN',
                ],
            ],
        ]);
    }

    protected function getGroupedFaqs(): array
    {
        $categories = get_terms([
            'taxonomy'   => 'faq-category',
            'orderby'    => 'term_order',
            'hide_empty' => false,
        ]);

        $grouped_faqs = [];

        foreach ($categories as $category) {
            $posts = get_posts([
                'post_type'      => 'faq',
                'posts_per_page' => -1,
                'order'          => 'DESC',
                'orderby'        => 'date',
                'tax_query'      => [
                    [
                        'taxonomy' => 'faq-category',
                        'field'    => 'term_id',
                        'terms'    => $category->term_id,
                    ],
                ],
            ]);

            $grouped_faqs[$category->term_id] = [
                'category' => $category,
                'posts'    => $posts,
            ];
        }

        $uncategorized_posts = get_posts([
            'post_type'      => 'faq',
            'posts_per_page' => -1,
            'order'          => 'DESC',
            'orderby'        => 'date',
            'tax_query'      => [
                [
                    'taxonomy' => 'faq-category',
                    'operator' => 'NOT EXISTS',
                ],
            ],
        ]);

        if (!empty($uncategorized_posts)) {
            $grouped_faqs['uncategorized'] = [
                'category' => (object) [
                    'term_id' => 'uncategorized',
                    'name'    => 'Overig',
                    'slug'    => 'overig',
                ],
                'posts' => $uncategorized_posts,
            ];
        }

        return $grouped_faqs;
    }

    protected function getDefaultFaqPosts(): array
    {
        return get_posts([
            'post_type'      => 'faq',
            'posts_per_page' => 12,
            'order'          => 'DESC',
        ]);
    }

    protected function formatFaqPosts(array $posts): array
    {
        return array_map(function ($post) {
            return [
                'id'       => $post->ID,
                'title'    => apply_filters('the_title', $post->post_title),
                'category' => get_the_terms($post->ID, 'faq-category'),
                'content'  => apply_filters('the_content', $post->post_content),
            ];
        }, $posts);
    }

    public function with(): array
    {
        return array_merge(
            $this->getCommonFields(),
            [
                'input' => get_field('input'),
                'media' => get_field('media'),
                'faq'   => $this->getFaq(),
                'faqs'  => get_field('faqs'),
            ]
        );
    }

    public function fields(): \StoutLogic\AcfBuilder\FieldsBuilder
    {
        $acfFields = new FieldsBuilder('Faq');

        return $acfFields;
    }

    public function enqueue(): void
    {
        //
    }
}
