<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;
use App\Helpers\LordIconHelper;

class SingleServices extends Composer
{
    protected static $views = [
        'single-services',
    ];

    public function getRelatedServices(): array
    {
        global $post;

        $services = get_posts([
            'post_type'      => 'services',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'exclude'        => [$post->ID],
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
        ]);

        return array_map(function ($service) {
            $icon = get_field('icon', $service->ID);
            return [
                'id'                => $service->ID,
                'title'             => apply_filters('the_title', $service->post_title),
                'link'              => get_permalink($service->ID),
                'icon'              => $icon,
                'icon_url'          => LordIconHelper::getIconUrl($icon),
                'image'             => get_field('image', $service->ID) ?: get_post_thumbnail_id($service->ID),
                'short_description' => get_field('short_description', $service->ID) ?: get_the_excerpt($service->ID),
                'width'             => '1/3',
            ];
        }, $services);
    }

    public function with(): array
    {
        global $post;

        $icon = get_field('icon', $post->ID);

        return [
            'title'            => get_the_title(),
            'featured_image'   => get_post_thumbnail_id($post->ID),
            'icon'             => $icon['url'] ?? null,
            'related_services' => $this->getRelatedServices(),
        ];
    }
}
