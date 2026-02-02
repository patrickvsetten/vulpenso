<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;
use App\Helpers\LordIconHelper;

class Header extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'sections.header',
    ];

    /**
     * Get services for the navigation.
     */
    protected function getServices(): array
    {
        $services = get_posts([
            'post_type'      => 'services',
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        ]);

        return array_map(function ($service) {
            $icon = get_field('icon', $service->ID);

            return [
                'id'       => $service->ID,
                'image'    => get_field('image', $service->ID) ?: get_post_thumbnail_id($service->ID),
                'link'     => get_permalink($service->ID),
                'title'    => apply_filters('the_title', $service->post_title),
                'icon'     => $icon,
                'icon_url' => LordIconHelper::getIconUrl($icon),
            ];
        }, $services);
    }

    /**
     * Get mobile bottom menu items from WordPress menu (first 3 items).
     */
    protected function getMobileMenuItems(): array
    {
        $menu_id = get_nav_menu_locations()['mobile_navigation'] ?? 0;

        if (!$menu_id) {
            return [];
        }

        $menu_items = wp_get_nav_menu_items($menu_id) ?: [];
        $top_level_items = array_filter($menu_items, fn($item) => $item->menu_item_parent == 0);
        $top_level_items = array_slice($top_level_items, 0, 3);
        $current_url = trailingslashit(home_url(add_query_arg([], $_SERVER['REQUEST_URI'] ?? '')));

        return array_map(function ($item) use ($current_url) {
            $icon = get_field('menu_item_icon', $item->ID);
            $item_url = trailingslashit($item->url);

            return [
                'id'        => $item->ID,
                'title'     => $item->title,
                'url'       => $item->url,
                'target'    => $item->target,
                'icon'      => $icon,
                'icon_url'  => LordIconHelper::getIconUrl($icon),
                'is_active' => ($item_url === $current_url) || in_array('current-menu-item', $item->classes ?? []),
            ];
        }, $top_level_items);
    }

    /**
     * Get secondary mobile menu items with parent/child structure.
     */
    protected function getSecondaryMobileMenu(): array
    {
        $menu_id = get_nav_menu_locations()['secondary_mobile_navigation'] ?? 0;

        if (!$menu_id) {
            return [];
        }

        $menu_items = wp_get_nav_menu_items($menu_id) ?: [];
        $menu_tree = [];
        $children = [];

        foreach ($menu_items as $item) {
            $item_data = [
                'id'        => $item->ID,
                'title'     => $item->title,
                'url'       => $item->url,
                'target'    => $item->target,
                'is_active' => in_array('current-menu-item', $item->classes ?? []),
            ];

            if ($item->menu_item_parent == 0) {
                $item_data['children'] = [];
                $menu_tree[$item->ID] = $item_data;
            } else {
                $children[$item->menu_item_parent][] = $item_data;
            }
        }

        foreach ($children as $parent_id => $child_items) {
            if (isset($menu_tree[$parent_id])) {
                $menu_tree[$parent_id]['children'] = $child_items;
            }
        }

        return array_values($menu_tree);
    }

    /**
     * Determine if menu spacer should be shown.
     */
    protected function shouldShowMenuSpacer(): bool
    {
        global $post;

        if (is_page_template('archive-news.blade.php')) {
            return true;
        }

        if ($post && is_singular('news')) {
            return false;
        }

        if ($post && basename(get_page_template()) === 'page.blade.php') {
            $blocks = parse_blocks($post->post_content);
            if (!empty($blocks) && $blocks[0]['blockName'] === 'acf/header') {
                return false;
            }
        }

        return true;
    }

    /**
     * Data to be passed to the view.
     */
    public function with(): array
    {
        return [
            'menu_spacer'           => $this->shouldShowMenuSpacer(),
            'services'              => $this->getServices(),
            'service_menu_image'    => get_field('service_menu_image', 'option'),
            'whatsapp_url'          => get_field('whatsapp_url', 'option'),
            'phone_general'         => get_field('phone_general', 'option'),
            'mobile_menu_items'     => $this->getMobileMenuItems(),
            'secondary_mobile_menu' => $this->getSecondaryMobileMenu(),
        ];
    }
}