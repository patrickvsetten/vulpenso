<?php
function create_employees() {
	$post_type = 'employees';

	register_post_type( $post_type, [
		'labels' => array(
			'name' => __('Medewerkers'),
			'singular_name' => __('Medewerker'),
			'add_new' => __('Nieuwe medewerker'),
			'add_new_item' => __('Nieuwe medewerker'),
			'edit_item' => __('Bewerk medewerker'),
			'new_item' => __('Nieuwe medewerker'),
			'view_item' => __('Bekijk medewerker'),
			'search_items' => __('Zoek medewerker'),
			'not_found' =>  __('Geen medewerker gevonden'),
			'not_found_in_trash' => __('Geen medewerker gevonden'),
			'parent_item_colon' => ''
		),
		'public' => false,
		'publicly_queryable' => false,
		'has_archive' => false,
		'show_ui' => true,
		'query_var' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'show_in_rest' => true,
		'supports' => array( 'title', 'thumbnail', 'page-attributes' ),
		'menu_icon' => 'dashicons-groups',
	]);
}

add_action( 'init', 'create_employees' );
