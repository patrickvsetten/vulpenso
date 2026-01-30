<?php

/**
 * Theme setup.
 */

namespace App;

use Illuminate\Support\Facades\Vite;

include(get_template_directory() . '/resources/posttypes/autoload.php');

add_action('acf/include_field_types', function() {
  new \App\Fields\LordIconField();
});

// Debug: Check if ACF is loaded
add_action('admin_notices', function() {
    if (!class_exists('ACF')) {
        echo '<div class="notice notice-error"><p>ACF plugin is not active. Lordicon field will not work.</p></div>';
    }
});

// Autoload Helpers
spl_autoload_register(function ($class) {
    $prefix = 'App\\Helpers\\';
    $base_dir = get_template_directory() . '/app/Helpers/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . $relative_class . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

add_action('wp_head', function () {
  ?>
  <!-- Google Fonts Optimization -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Outfit:wght@400..900&display=optional">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=optional">
  <?php

  // Preload mobile nav icons (visible immediately on mobile)
  if (wp_is_mobile()) {
    $locations = get_nav_menu_locations();
    $menu_id = $locations['mobile_navigation'] ?? 0;

    if ($menu_id) {
      $menu_items = wp_get_nav_menu_items($menu_id) ?: [];
      $top_level_items = array_filter($menu_items, fn($item) => $item->menu_item_parent == 0);
      $top_level_items = array_slice($top_level_items, 0, 3);

      foreach ($top_level_items as $item) {
        $icon = get_field('menu_item_icon', $item->ID);
        if ($icon) {
          $icon_url = \App\Helpers\LordIconHelper::getIconUrl($icon);
          if ($icon_url) {
            echo '<link rel="preload" href="' . esc_url($icon_url) . '" as="fetch" crossorigin>';
          }
        }
      }
    }
  }
}, 1);


/**
 * Inject styles into the block editor.
 *
 * @return array
 */
add_filter('block_editor_settings_all', function ($settings) {
    $style = Vite::asset('resources/css/editor.css');

    $settings['styles'][] = [
        'css' => "@import url('{$style}')",
    ];

    return $settings;
});

/**
 * Inject scripts into the block editor.
 *
 * @return void
 */
add_filter('admin_head', function () {
    if (! get_current_screen()?->is_block_editor()) {
        return;
    }

    $dependencies = json_decode(Vite::content('editor.deps.json'));

    foreach ($dependencies as $dependency) {
        if (! wp_script_is($dependency)) {
            wp_enqueue_script($dependency);
        }
    }

    echo Vite::withEntryPoints([
        'resources/js/editor.js',
    ])->toHtml();
});

/**
 * Use the generated theme.json file.
 *
 * @return string
 */
add_filter('theme_file_path', function ($path, $file) {
    return $file === 'theme.json'
        ? public_path('build/assets/theme.json')
        : $path;
}, 10, 2);

/**
 * Register the initial theme setup.
 *
 * @return void
 */
add_action('after_setup_theme', function () {
    /**
     * Disable full-site editing support.
     *
     * @link https://wptavern.com/gutenberg-10-5-embeds-pdfs-adds-verse-block-color-options-and-introduces-new-patterns
     */
    remove_theme_support('block-templates');

    /**
     * Register the navigation menus.
     *
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'vulpenso'),
        'secondary_navigation' => __('Secondary Navigation', 'vulpenso'),
        'mobile_navigation' => __('Mobile Navigation', 'vulpenso'),
        'secondary_mobile_navigation' => __('Secondary Mobile Navigation', 'vulpenso'),
        'footer_navigation' => __('Footer Navigation', 'vulpenso'),
        'legals_navigation' => __('Legals Navigation', 'vulpenso'),
    ]);

    /**
     * Disable the default block patterns.
     *
     * @link https://developer.wordpress.org/block-editor/developers/themes/theme-support/#disabling-the-default-block-patterns
     */
    remove_theme_support('core-block-patterns');

    /**
     * Enable plugins to manage the document title.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Enable post thumbnail support.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable responsive embed support.
     *
     * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#responsive-embedded-content
     */
    add_theme_support('responsive-embeds');

    /**
     * Enable HTML5 markup support.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', [
        'caption',
        'comment-form',
        'comment-list',
        'gallery',
        'search-form',
        'script',
        'style',
    ]);

    /**
     * Enable selective refresh for widgets in customizer.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#customize-selective-refresh-widgets
     */
    add_theme_support('customize-selective-refresh-widgets');
}, 20);

/**
 * Register the theme sidebars.
 *
 * @return void
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ];

    register_sidebar([
        'name' => __('Primary', 'florijnwinterloop'),
        'id' => 'sidebar-primary',
    ] + $config);

    register_sidebar([
        'name' => __('Footer', 'florijnwinterloop'),
        'id' => 'sidebar-footer',
    ] + $config);
});