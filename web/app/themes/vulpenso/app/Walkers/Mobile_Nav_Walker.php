<?php

namespace App\Walkers;

use Walker_Nav_Menu;
// use App\Helpers\VacancyCountHelper;

class Mobile_Nav_Walker extends Walker_Nav_Menu
{
    private $item_index = 0;
    private $is_top_level = true;
    private $submenu_index = 0;

    public function start_lvl( &$output, $depth = 0, $args = null ) {
        $this->is_top_level = false;
        $this->submenu_index = 0;
        $output .= '<ul class="sub-menu pt-4 text-dark divide-y divide-white/10 space-y-4">';
    }

    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $hasChildren = in_array('menu-item-has-children', $item->classes);
        $classes = implode(' ', $item->classes);

        // Simple classes for transitions - no inline styles
        if ($this->is_top_level) {
            $output .= '<li class="mobile-nav-item text-xl uppercase ' . $classes . '" data-index="' . $this->item_index . '">';
            $this->item_index++;
        } else {
            $output .= '<li class="mobile-nav-submenu-item text-base uppercase ' . $classes . '" data-index="' . $this->submenu_index . '">';
            $this->submenu_index++;
        }

        if ($hasChildren) {
            $output .= '
            <div class="w-full" x-data="{ open: false }">
              <div class="flex justify-between items-center pb-4">
                <a href="' . $item->url . '" class="uppercase flex-1 text-left">' . $item->title . '</a>
                <button @click="open = !open" class="ml-4 pl-4 p-1 flex-shrink-0 border-l border-white/20">
                  <svg :class="{ \'-rotate-180\': open }" class="size-3 transition-transform" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 1L5 5L1 1" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </button>
              </div>
              <ul x-show="open" x-collapse class="sub-menu text-sm space-y-2 opacity-60">
            ';
        } else {
            $output .= '<a href="' . $item->url . '" class="flex items-center gap-4 pb-4">' . $item->title . '</a>';
        }
    }

    public function end_el( &$output, $item, $depth = 0, $args = null ) {
        if (in_array('menu-item-has-children', $item->classes)) {
            $output .= '</ul></div></li>';
        } else {
            $output .= '</li>';
        }
    }

    public function end_lvl( &$output, $depth = 0, $args = null ) {
        $this->is_top_level = true;
        $output .= '</ul>';
    }
}
