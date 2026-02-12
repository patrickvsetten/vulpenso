<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class SingleNews extends Composer
{
    protected static $views = [
        'single-news',
    ];

    public function with(): array
    {
        $post_id = get_the_ID();
        $categories = get_the_terms($post_id, 'news_category');
        $first_category = ($categories && !is_wp_error($categories)) ? $categories[0] : null;

        return [
            'post_id' => $post_id,
            'thumbnail_id' => get_post_thumbnail_id($post_id),
            'first_category' => $first_category,
            'date' => get_the_date('j F Y', $post_id),
            'related_posts' => $this->getRelatedPosts($post_id, $first_category),
        ];
    }

    protected function getRelatedPosts(int $post_id, ?object $first_category): array
    {
        $args = [
            'post_type' => 'news',
            'posts_per_page' => 3,
            'post__not_in' => [$post_id],
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC',
        ];

        if ($first_category) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'news_category',
                    'field' => 'term_id',
                    'terms' => $first_category->term_id,
                ],
            ];
        }

        $related_query = new \WP_Query($args);
        $related_posts = $related_query->posts;

        if (count($related_posts) < 3) {
            $exclude_ids = array_merge([$post_id], wp_list_pluck($related_posts, 'ID'));
            $fallback_query = new \WP_Query([
                'post_type' => 'news',
                'posts_per_page' => 3 - count($related_posts),
                'post__not_in' => $exclude_ids,
                'post_status' => 'publish',
                'orderby' => 'date',
                'order' => 'DESC',
            ]);
            $related_posts = array_merge($related_posts, $fallback_query->posts);
        }

        return $related_posts;
    }
}
