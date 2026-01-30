<?php

namespace App\Walkers;

use Walker_Nav_Menu;

class Primary_Nav_Walker extends Walker_Nav_Menu
{
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        $classes = implode(' ', array_filter($item->classes));
        $output .= '<li class="' . esc_attr($classes) . '">';

        // Only apply hover effect to top-level items
        if ($depth === 0) {
            $output .= '<a href="' . esc_url($item->url) . '" class="nav-hover-link">';
            $output .= '<span class="nav-hover-mask"><span class="nav-hover-text">' . esc_html($item->title) . '</span></span>';
            $output .= '</a>';
        } else {
            $output .= '<a href="' . esc_url($item->url) . '">' . esc_html($item->title) . '</a>';
        }
    }

    public function end_el(&$output, $item, $depth = 0, $args = null)
    {
        $output .= '</li>';
    }
}
