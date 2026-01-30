<?php
function create_faq() {
	$post_type = 'faq';

	register_post_type( $post_type, [
		'labels' => array(
			'name' => __('Veelgestelde vragen'),
			'singular_name' => __('Veelgestelde vragen'),
			'add_new' => __('Nieuw item'),
			'add_new_item' => __('Nieuw item'),
			'edit_item' => __('Bewerk item'),
			'new_item' => __('Nieuw item'),
			'view_item' => __('Bekijk item'),
			'search_items' => __('Zoek item'),
			'not_found' =>  __('Geen item gevonden'),
			'not_found_in_trash' => __('Geen item gevonden'),
			'parent_item_colon' => ''
		),
		'public' => true,
		'has_archive' => false,
		'show_ui' => true,
		'query_var' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'show_in_rest' => true,
		'supports' => array( 'author', 'editor', 'excerpt', 'title', 'thumbnail', 'revisions', 'page-attributes' ),
		'rewrite' => array( 'slug' => '/faq', 'with_front' => false ),
		'menu_icon' => 'dashicons-format-chat',
	]);

  register_taxonomy('faq-category', $post_type, [
      'hierarchical'      => true,
      'show_in_rest'      => true,
      'show_admin_column' => true,
      'labels'            => [
          'name'          => 'Categorie',
          'add_new_item'  => 'Nieuwe categorie',
          'edit_item'     => 'Bewerk categorie'
      ],
  ]);

  flush_rewrite_rules();
}

add_action( 'init', 'create_faq' );
