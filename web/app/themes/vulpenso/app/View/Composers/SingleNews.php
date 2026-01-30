<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class SingleNews extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'partials.content-single-news',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'reading_time' => $this->getReadingTime(),
            'categories' => $this->getCategories(),
            'contact_box' => get_field('news_contact_box', 'option') ?: [],
            'social_links' => get_field('social_links', 'option') ?: [],
            'related_news' => $this->getRelatedNews(),
        ];
    }

    /**
     * Calculate reading time based on word count.
     *
     * @return int
     */
    protected function getReadingTime(): int
    {
        $content = get_the_content();
        $word_count = str_word_count(strip_tags($content));

        // Average reading speed: 200 words per minute
        return max(1, (int) ceil($word_count / 200));
    }

    /**
     * Get news categories for the current post.
     *
     * @return array|false
     */
    protected function getCategories()
    {
        return get_the_terms(get_the_ID(), 'news-category');
    }

    /**
     * Get related news posts (excluding current post).
     *
     * @return array
     */
    protected function getRelatedNews(): array
    {
        $current_id = get_the_ID();

        $posts = get_posts([
            'post_type' => 'news',
            'posts_per_page' => 8,
            'post__not_in' => [$current_id],
            'orderby' => 'date',
            'order' => 'DESC',
        ]);

        return array_map(function ($post) {
            $word_count = str_word_count(strip_tags($post->post_content));
            $reading_time = max(1, (int) ceil($word_count / 200));
            $categories = get_the_terms($post->ID, 'news-category');

            return [
                'id' => $post->ID,
                'title' => $post->post_title,
                'url' => get_permalink($post->ID),
                'thumbnail' => get_post_thumbnail_ID($post->ID),
                'categories' => $categories && !is_wp_error($categories) ? $categories : [],
                'reading_time' => $reading_time,
            ];
        }, $posts);
    }
}
