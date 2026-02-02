<?php

namespace App\Blocks;

use StoutLogic\AcfBuilder\FieldsBuilder;

class News extends BaseBlock
{
    public $name = 'Nieuws';
    public $description = 'Toont de laatste nieuwsberichten.';
    public $category = 'formatting';
    public $keywords = ['nieuws', 'news', 'berichten'];
    public $post_types = [];
    public $parent = [];
    public $mode = 'edit';
    public $view = 'blocks.news';

    public $supports = [
        'full_height' => false,
        'anchor' => false,
        'mode' => 'edit',
        'multiple' => true,
        'supports' => ['mode' => false],
        'jsx' => true,
    ];

    public function with(): array
    {
        return array_merge(
            $this->getCommonFields(),
            [
                'archive_link' => get_field('archive_link'),
                'posts'        => $this->getPosts(),
            ]
        );
    }

    protected function getPosts(): array
    {
        $posts = get_posts([
            'post_type'      => 'news',
            'posts_per_page' => 3,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]);

        return array_map(function ($post) {
            $categories = get_the_terms($post->ID, 'news_category');
            $category = $categories && !is_wp_error($categories) ? $categories[0]->name : null;

            return [
                'id'        => $post->ID,
                'title'     => get_the_title($post),
                'excerpt'   => get_the_excerpt($post),
                'permalink' => get_permalink($post),
                'date'      => get_the_date('d-m-Y', $post),
                'thumbnail' => get_post_thumbnail_id($post),
                'category'  => $category,
            ];
        }, $posts);
    }

    public function fields(): \StoutLogic\AcfBuilder\FieldsBuilder
    {
        $acfFields = new FieldsBuilder('news');

        return $acfFields;
    }

    public function enqueue(): void
    {
        //
    }
}
