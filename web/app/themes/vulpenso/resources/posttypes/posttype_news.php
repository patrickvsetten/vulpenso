<?php
function create_news() {
	$post_type = 'news';

	register_post_type( $post_type, [
		'labels' => array(
			'name' => __('Nieuws'),
			'singular_name' => __('Nieuwsbericht'),
			'add_new' => __('Nieuw bericht'),
			'add_new_item' => __('Nieuw nieuwsbericht'),
			'edit_item' => __('Bewerk nieuwsbericht'),
			'new_item' => __('Nieuw nieuwsbericht'),
			'view_item' => __('Bekijk nieuwsbericht'),
			'search_items' => __('Zoek nieuwsbericht'),
			'not_found' =>  __('Geen nieuwsberichten gevonden'),
			'not_found_in_trash' => __('Geen nieuwsberichten gevonden'),
			'parent_item_colon' => ''
		),
		'public' => true,
		'has_archive' => true,
		'show_ui' => true,
		'query_var' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'show_in_rest' => true,
		'supports' => array( 'author', 'editor', 'excerpt', 'title', 'thumbnail', 'revisions', 'page-attributes' ),
		'rewrite' => array( 'slug' => 'nieuws', 'with_front' => false ),
		'menu_icon' => 'dashicons-megaphone',
	]);

	register_taxonomy( 'news_category', $post_type, [
		'labels' => array(
			'name' => __('Categorieën'),
			'singular_name' => __('Categorie'),
			'search_items' => __('Zoek categorieën'),
			'all_items' => __('Alle categorieën'),
			'parent_item' => __('Hoofdcategorie'),
			'parent_item_colon' => __('Hoofdcategorie:'),
			'edit_item' => __('Bewerk categorie'),
			'update_item' => __('Update categorie'),
			'add_new_item' => __('Nieuwe categorie'),
			'new_item_name' => __('Nieuwe categorienaam'),
			'menu_name' => __('Categorieën'),
		),
		'hierarchical' => true,
		'show_ui' => true,
		'show_in_rest' => true,
		'show_admin_column' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'nieuws/categorie', 'with_front' => false ),
	]);
}

add_action( 'init', 'create_news' );
