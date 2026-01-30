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

    public function getServices() {
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
              'image'    => get_field('image', $service->ID),
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
    public function getMobileMenuItems()
    {
        $locations = get_nav_menu_locations();
        $menu_id = $locations['mobile_navigation'] ?? 0;

        if (!$menu_id) {
            return [];
        }

        $menu_items = wp_get_nav_menu_items($menu_id) ?: [];
        // Get only the first 3 top-level items
        $top_level_items = array_filter($menu_items, fn($item) => $item->menu_item_parent == 0);
        $top_level_items = array_slice($top_level_items, 0, 3);

        $current_url = trailingslashit(home_url(add_query_arg([], $_SERVER['REQUEST_URI'] ?? '')));

        return array_map(function ($item) use ($current_url) {
            $icon = get_field('menu_item_icon', $item->ID);
            $item_url = trailingslashit($item->url);
            $is_active = ($item_url === $current_url) || in_array('current-menu-item', $item->classes ?? []);

            return [
                'id'        => $item->ID,
                'title'     => $item->title,
                'url'       => $item->url,
                'target'    => $item->target,
                'icon'      => $icon,
                'icon_url'  => LordIconHelper::getIconUrl($icon),
                'is_active' => $is_active,
            ];
        }, $top_level_items);
    }

    /**
     * Get secondary mobile menu items with parent/child structure.
     */
    public function getSecondaryMobileMenu()
    {
        $locations = get_nav_menu_locations();
        $menu_id = $locations['secondary_mobile_navigation'] ?? 0;

        if (!$menu_id) {
            return [];
        }

        $menu_items = wp_get_nav_menu_items($menu_id) ?: [];

        // Organize items in parent/child structure
        $menu_tree = [];
        $children = [];

        foreach ($menu_items as $item) {
            $is_active = in_array('current-menu-item', $item->classes ?? []);

            if ($item->menu_item_parent == 0) {
                $menu_tree[$item->ID] = [
                    'id'        => $item->ID,
                    'title'     => $item->title,
                    'url'       => $item->url,
                    'target'    => $item->target,
                    'is_active' => $is_active,
                    'children'  => [],
                ];
            } else {
                $children[$item->menu_item_parent][] = [
                    'id'        => $item->ID,
                    'title'     => $item->title,
                    'url'       => $item->url,
                    'target'    => $item->target,
                    'is_active' => $is_active,
                ];
            }
        }

        // Attach children to parents
        foreach ($children as $parent_id => $child_items) {
            if (isset($menu_tree[$parent_id])) {
                $menu_tree[$parent_id]['children'] = $child_items;
            }
        }

        return array_values($menu_tree);
    }

    public function getNavLayout() {
      global $post;

      $menu_space = true;

      if ($post && basename(get_page_template()) === 'page.blade.php') {
        $blocks = parse_blocks($post->post_content);

        if (!empty($blocks)) {
            if ($blocks[0]['blockName'] === 'acf/header') {
                $menu_space = false;
            }
        }
      }

      if (($post && is_singular('news'))) {
        $menu_space = false;
      }

      if ( is_page_template('archive-news.blade.php')) {
        $menu_space = true;
      }

      return [
          'menu_space'       => $menu_space,
      ];
  }

    /**
     * Data to be passed to the view.
     */
    public function with()
    {
        $navLayout = $this->getNavLayout();

        return [
          'menu_spacer'             => $navLayout['menu_space'],
          'services'                => $this->getServices(),
          'whatsapp_url'            => get_field('whatsapp_url', 'option'),
          'phone_general'           => get_field('phone_general', 'option'),
          'mobile_menu_items'       => $this->getMobileMenuItems(),
          'secondary_mobile_menu'   => $this->getSecondaryMobileMenu(),
        ];
    }
}