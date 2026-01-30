<?php

namespace App\Blocks;

use StoutLogic\AcfBuilder\FieldsBuilder;

class Faq extends BaseBlock
{
    public $name = 'FAQ';
    public $description = 'Veelgestelde vragen.';
    public $category = 'formatting';
    public $keywords = [];
    public $post_types = [];
    public $parent = [];
    public $mode = 'edit';
    public $view = 'blocks.faq';

    public $supports = [
        'full_height' => false,
        'anchor' => false,
        'mode' => 'edit',
        'multiple' => true,
        'supports' => array('mode' => false),
        'jsx' => true,
    ];

    public function getFaq() {

    $input = get_field('input');
    $faq = [];

    if ($input === 'choose') {
        $faq = get_field('questions');
    }
    elseif ($input === 'category') {
        $terms = get_field('categories');

        if (!empty($terms)) {
            $faq = get_posts([
                'post_type' => 'faq',
                'posts_per_page' => -1,
                'order'   => 'DESC',
                'tax_query' => [
                    [
                        'taxonomy' => 'faq-category',
                        'field' => 'term_id',
                        'terms' => $terms,
                        'operator' => 'IN',
                    ],
                ],
            ]);
        } else {
            $faq = get_posts([
                'post_type' => 'faq',
                'posts_per_page' => '12',
                'order'   => 'DESC',
            ]);
        }
    }
    elseif ($input === 'all') {
      // Haal alle categorieën op in de door jou ingestelde volgorde
      $categories = get_terms([
          'taxonomy' => 'faq-category',
          'orderby' => 'term_order',
          'hide_empty' => false, // Ook lege categorieën tonen indien nodig
      ]);
  
      $grouped_faqs = [];
  
      foreach ($categories as $category) {
          // Haal alle FAQ's op binnen deze categorie
          $posts = get_posts([
              'post_type' => 'faq',
              'posts_per_page' => -1,
              'order'   => 'DESC',
              'orderby' => 'date',
              'tax_query' => [
                  [
                      'taxonomy' => 'faq-category',
                      'field' => 'term_id',
                      'terms' => $category->term_id,
                  ],
              ],
          ]);
  
          // Voeg toe aan de gestructureerde array
          $grouped_faqs[$category->term_id] = [
              'category' => $category,
              'posts' => $posts
          ];
      }
  
      // FAQ's zonder categorie
      $uncategorized_posts = get_posts([
          'post_type' => 'faq',
          'posts_per_page' => -1,
          'order'   => 'DESC',
          'orderby' => 'date',
          'tax_query' => [
              [
                  'taxonomy' => 'faq-category',
                  'operator' => 'NOT EXISTS',
              ],
          ],
      ]);
  
      if (!empty($uncategorized_posts)) {
          $grouped_faqs['uncategorized'] = [
              'category' => (object)[
                  'term_id' => 'uncategorized',
                  'name' => 'Overig',
                  'slug' => 'overig'
              ],
              'posts' => $uncategorized_posts
          ];
      }
  
      return $grouped_faqs;
    }
   else {
        $faq = get_posts([
            'post_type' => 'faq',
            'posts_per_page' => '12',
            'order'   => 'DESC',
        ]);
    }

    return array_map(function ($post) {
        return [
            'id'         => $post->ID,
            'title'      => apply_filters('the_title', $post->post_title),
            'category'   => get_the_terms($post->ID, 'faq-category'),
            'content'    => apply_filters('the_content', $post->post_content),
        ];
    }, $faq);
}

    public function with()
    {
        return array_merge(
            $this->getCommonFields(),
            [
                'input'                 => get_field('input'),  
                'media'                 => get_field('media'),
                'faq'                   => $this->getFaq(),
                'faqs'                  => get_field('faqs'),
            ]
        );
    }

    public function fields()
    {
        $acfFields = new FieldsBuilder('Faq');

        return $acfFields->build();
    }

    public function enqueue()
    {
        //
    }
}
