<?php
function create_services() {
	$post_type = 'services';

	register_post_type( $post_type, [
		'labels' => array(
			'name' => __('Diensten'),
			'singular_name' => __('Dienst'),
			'add_new' => __('Nieuwe dienst'),
			'add_new_item' => __('Nieuwe dienst'),
			'edit_item' => __('Bewerk dienst'),
			'new_item' => __('Nieuwe dienst'),
			'view_item' => __('Bekijk dienst'),
			'search_items' => __('Zoek dienst'),
			'not_found' =>  __('Geen dienst gevonden'),
			'not_found_in_trash' => __('Geen dienst gevonden'),
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
		'rewrite' => array( 'slug' => '/wat-we-doen', 'with_front' => false ),
		'menu_icon' => 'dashicons-tag',
	]);
}

add_action( 'init', 'create_services' );
