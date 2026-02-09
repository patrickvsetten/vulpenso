<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class SingleLandingspages extends Composer
{

    public function getStyles() {
      $styles = [];

      $styles_query = get_posts([
          'post_type'      => 'styles',
          'posts_per_page' => -1,
          'order'          => 'DESC',
          'tax_query'      => [
              [
                  'taxonomy' => 'category_styles',
                  'field'    => 'term_id',
                  'terms'    => 15,
                  'operator' => 'IN',
              ],
          ],
      ]);

      foreach ($styles_query as $style) {
          // Voeg alleen de child posts toe aan de resultaten
          if ($style->post_parent != 0) {
              $styles[] = [
                  'id'             => $style->ID,
                  'title'          => apply_filters('the_title', $style->post_title),
                  'permalink'      => get_permalink($style->ID),
                  'featured_image' => get_post_thumbnail_id($style->ID),
              ];
          }
      }

      return $styles;
    }

    public function override()
    {
      global $post;

      return [
        'id'                  => $post->ID,
        'title'               => apply_filters('the_title', $post->post_title),
        'featured_image'      => get_post_thumbnail_id( $post->ID ),
        'trigger'             => get_field( 'trigger', $post->ID ),
        'title_trigger'       => get_field( 'title_trigger', $post->ID ),
        'content_trigger'     => get_field( 'content_trigger', $post->ID ),
        'buttons_trigger'     => get_field( 'buttons_trigger', $post->ID ),
        'title_basic'         => get_field( 'title_landingspage', 'option' ),
        'content_basic'       => get_field( 'content_landingspage', 'option' ),
        'buttons_basic'       => get_field( 'buttons_landingspage', 'option' ),
        'slides'              => $this->getStyles(),
        'cta_image'           => get_field( 'landingspage_cta_image', 'option' ),
        'cta_title'           => get_field( 'landingspage_cta_title', 'option' ),
        'cta_links'           => get_field( 'landingspage_cta_links', 'option' ) ?: [],
      ];
    }
}
